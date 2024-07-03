from flask import Flask, jsonify, request
from flask_cors import CORS
import logging
import pandas as pd
import numpy as np
from sklearn.preprocessing import StandardScaler
from sklearn.cluster import DBSCAN
from sklearn.metrics import silhouette_score
import json

app = Flask(__name__)
CORS(app)

logging.basicConfig(level=logging.INFO)

# Function to perform DBSCAN trials
def dbscan_trials(data, eps_values, min_samples_values):
    results = []
    best_score = -1
    best_config = None
    best_labels = None

    for eps in eps_values:
        for min_samples in min_samples_values:
            try:
                db = DBSCAN(eps=eps, min_samples=min_samples).fit(data)
                labels = db.labels_

                if len(set(labels)) > 1:
                    silhouette_avg = silhouette_score(data, labels)
                    num_clusters = len(set(labels)) - (1 if -1 in labels else 0)
                    noise_points = np.sum(labels == -1)
                    clustered_points = len(labels) - noise_points
                    result = {
                        "EPS": eps,
                        "MINPTS": min_samples,
                        "NUM_CLUSTERS": num_clusters,
                        "NUM_NOISE": noise_points,
                        "NUM_CLUSTERED": clustered_points,
                        "SILHOUETTE_INDEX": silhouette_avg
                    }

                    results.append(result)

                    if silhouette_avg > best_score:
                        best_score = silhouette_avg
                        best_config = result
                        best_labels = labels

            except Exception as e:
                logging.error("Error in DBSCAN trial: %s", str(e))

    return results, best_config, best_labels

@app.route('/clustering', methods=['POST'])
def getData():
    try:
        request_data = request.get_json()
        data = pd.DataFrame(request_data)
        a = data.loc[:33, ["tahun", "namaprovinsi", "luaspanen", "produktivitas", "produksi"]]
        data_values = a.iloc[:, 2:].values.astype(float)
        scaler = StandardScaler()
        data_scaled = scaler.fit_transform(data_values)

        eps_values = np.linspace(0.1, 5, 50)
        min_samples_values = range(2, 10)

        results, best_config, best_labels = dbscan_trials(data_scaled, eps_values, min_samples_values)
        results_df = pd.DataFrame(results)
        year = int(a["tahun"].iloc[0])
        results_df['year'] = year

        # Group provinces by cluster labels
        province_names = a["namaprovinsi"].tolist()
        cluster_to_provinces = {}
        for label, province in zip(best_labels, province_names):
            if label == -1:
                continue  # Skip noise points
            cluster_key = f"cluster_{label}"
            if cluster_key not in cluster_to_provinces:
                cluster_to_provinces[cluster_key] = {"provinces": "", "year": year}
            if cluster_to_provinces[cluster_key]["provinces"]:
                cluster_to_provinces[cluster_key]["provinces"] += ", "
            cluster_to_provinces[cluster_key]["provinces"] += province

        # Convert cluster_to_provinces to the desired format
        province_clustered_data = [
            {"cluster": key, "provinces": value["provinces"], "year": value["year"]}
            for key, value in cluster_to_provinces.items()
        ]

        # Convert DataFrame results_df to JSON
        results_json = results_df.to_json(orient='records')

        return jsonify({"results": json.loads(results_json), "hasil_cluster": province_clustered_data})

    except Exception as e:
        logging.error("Error in getData function: %s", str(e))
        return jsonify({"error": "Failed to load and scale data"}), 500

@app.route("/")
def hello_world():
    return "Hello, World!"

if __name__ == '__main__':
    app.run(debug=True, port=8088)
