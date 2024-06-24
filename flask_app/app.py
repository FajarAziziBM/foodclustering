from flask import Flask, jsonify, request
from flask_cors import CORS
import requests
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
        a = data.loc[:33, ["namaprovinsi", "luaspanen", "produktivitas", "produksi"]]
        data_values = a.iloc[:, 1:].values.astype(float)
        scaler = StandardScaler()
        data_scaled = scaler.fit_transform(data_values)

        eps_values = np.linspace(0.1, 5, 50)
        min_samples_values = range(2, 10)

        results, best_config, best_labels = dbscan_trials(data_scaled, eps_values, min_samples_values)

        results_df = pd.DataFrame(results)

        best_labels_named = {a["namaprovinsi"][i]: int(label) for i, label in enumerate(best_labels)}
        logging.info("Best labels named: %s", best_labels_named)

        results_json = results_df.to_json(orient='records')

        data_to_send = {
            "results": json.loads(results_json),
            "province_clustered_data": best_labels_named
        }

        return jsonify(data_to_send)

        laravel_endpoint_url = "http://127.0.0.1:8080/api/getapidatas"
        try:
            response = requests.post(laravel_endpoint_url, data_to_send)
            response.raise_for_status()
            logging.info("Data sent successfully to backend Laravel!")
        except Exception as e:
            logging.error("Error occurred while sending data to backend Laravel: %s", e)
            return jsonify({"error": "Failed to send data to backend"}), 500

        return jsonify({"message": "Data loaded and scaled successfully", "province_clustered_data": best_labels_named})

    except Exception as e:
        logging.error("Error in getData function: %s", str(e))
        return jsonify({"error": "Failed to load and scale data"}), 500

@app.route("/")
def hello_world():
    return "Hello, World!"

if __name__ == '__main__':
    app.run(debug=True, port=8088)
