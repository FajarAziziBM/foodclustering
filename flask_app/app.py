from flask import Flask, jsonify, request
from flask_cors import CORS
import logging
import pandas as pd
import numpy as np
from sklearn.preprocessing import StandardScaler
from sklearn.cluster import DBSCAN
from sklearn.metrics import silhouette_score
from sklearn.neighbors import NearestNeighbors
import json

class DataPreprocessor:
    def __init__(self):
        self.scaler = StandardScaler()

    def preprocess(self, data):
        return self.scaler.fit_transform(data)

class EpsCalculator:
    def __init__(self, n_neighbors=3, threshold_factor=3):
        self.n_neighbors = n_neighbors
        self.threshold_factor = threshold_factor

    def calculate_eps_values(self, data):
        neighbors = NearestNeighbors(n_neighbors=self.n_neighbors)
        neighbors_fit = neighbors.fit(data)
        distances, _ = neighbors_fit.kneighbors(data)

        # Sort and compute differences
        distances = np.sort(distances[:, -1])
        differences = np.diff(distances)
        mean_diff = np.mean(differences)
        std_diff = np.std(differences)

        # Threshold for significant jumps
        threshold = self.threshold_factor * std_diff

        # Find indices where differences exceed threshold
        significant_jumps = np.where(differences > threshold)[0]
        eps_values = distances[significant_jumps]
        return eps_values

class DBSCANClusterer:
    def __init__(self):
        self.min_samples_values = range(3, 9)
        self.eps_calculator = EpsCalculator()

    def perform_trials(self, data):
        results = []
        best_score = -1
        best_config = None
        best_labels = None

        eps_values = self.eps_calculator.calculate_eps_values(data)

        for eps in eps_values:
            for min_samples in self.min_samples_values:
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

                        if silhouette_avg > best_score and num_clusters == 2:  # Ensure we have exactly 2 clusters
                            best_score = silhouette_avg
                            best_config = result
                            best_labels = labels

                except Exception as e:
                    logging.error("Error in DBSCAN trial: %s", str(e))

        return results, best_config, best_labels

class ClusteringAPI:
    def __init__(self):
        self.preprocessor = DataPreprocessor()
        self.clusterer = DBSCANClusterer()

    def process_data(self, request_data):
        try:
            data = pd.DataFrame(request_data)
            a = data.loc[:33, ["tahun", "namaprovinsi", "luaspanen", "produktivitas", "produksi"]]
            data_scaled = self.preprocessor.preprocess(a.iloc[:, 2:].values.astype(float))

            results, best_config, best_labels = self.clusterer.perform_trials(data_scaled)
            results_df = pd.DataFrame(results)
            year = int(a["tahun"].iloc[0])
            results_df['year'] = year

            province_names = a["namaprovinsi"].tolist()
            cluster_to_provinces = {}

            # Calculate overall silhouette score
            overall_silhouette = silhouette_score(data_scaled, best_labels)

            # Get unique cluster labels (excluding noise points)
            unique_clusters = sorted(set(label for label in best_labels if label != -1))
            num_clusters = len(unique_clusters)

            # Define categories based on number of clusters
            categories = ['rentan', 'tahan', 'sangat tahan']
            category_mapping = dict(zip(unique_clusters, categories[:num_clusters]))

            for label, province in zip(best_labels, province_names):
                if label == -1:
                    continue  # Skip noise points
                category = category_mapping[label]
                if category not in cluster_to_provinces:
                    cluster_to_provinces[category] = {"provinces": [], "year": year}
                cluster_to_provinces[category]["provinces"].append(province)

            # Format the output
            province_clustered_data = [
                {
                    "cluster": category,
                    "provinces": ", ".join(value["provinces"]),
                    "year": value["year"],
                    "silhouette_score": overall_silhouette
                }
                for category, value in cluster_to_provinces.items()
            ]

            results_json = results_df.to_json(orient='records')

            return {"results": json.loads(results_json), "hasil_cluster": province_clustered_data}

        except Exception as e:
            logging.error("Error in process_data function: %s", str(e))
            return {"error": "Failed to load and scale data"}

app = Flask(__name__)
CORS(app)

logging.basicConfig(level=logging.INFO)

clustering_api = ClusteringAPI()

@app.route('/clustering', methods=['POST'])
def get_data():
    try:
        request_data = request.get_json()
        result = clustering_api.process_data(request_data)
        return jsonify(result)
    except Exception as e:
        logging.error("Error in get_data function: %s", str(e))
        return jsonify({"error": "Failed to process data"}), 500

@app.route("/")
def hello_world():
    return "Hello, World!"

if __name__ == '__main__':
    app.run(debug=True, port=8088)
