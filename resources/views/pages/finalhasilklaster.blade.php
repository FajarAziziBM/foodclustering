@extends('layouts.app', [
    'class' => 'Hasil Klaster',
    'elementActive' => 'hasilklasterdbscan',
])

@section('content')
    <div class="content">
        <div class="col-md-12">
            <div class="card">
                <div class="row d-flex justify-content-end">
                    <div class="float-end col-2">
                        <form action="{{ route('hasilklasterdbscan') }}" method="GET" id="tahunForm">
                            <div class="input-group no-border p-3">
                                <select class="form-control" name="tahun" id="selectTahun" onchange="submitForm()">
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                            {{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h3 class="card-title" id="clusterTitle">Cluster {{ $selectedYear }}</h3>
                        <hr>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" style="width:100%" id="dataTable">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Cluster</th>
                                        <th class="text-right">Anggota Cluster</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clusterData as $cluster)
                                        <tr>
                                            <td>{{ $cluster->cluster }}</td>
                                            <td class="text-right">{{ $cluster->anggota_cluster }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Statistik Klaster</h5>
                            <hr>
                            <p class="card-category">Hasil Terbaik</p>

                        </div>
                        <div class="card-body">
                            <canvas id="clusterChart"></canvas>
                        </div>
                        <div class="card-footer">
                            <div class="legend">
                                <i class="fa fa-bar text-primary"></i> Jumlah Anggota Klaster
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h5 class="card-title">Silhouette Score Analysis</h5>
                            <hr>
                        </div>
                        <div class="card-body">
                            <canvas id="silhouetteChart"></canvas>
                        </div>
                        <div class="card-footer">

                            <hr />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function submitForm() {
            document.getElementById('tahunForm').submit();
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            fetch('{{ route('data.grafik') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.clusterData && data.clusterData.length > 0) {
                        renderBarChart(data.anggota); // Panggil fungsi renderBarChart dengan data anggota
                        renderLineChart(data.clusterData);
                    } else {
                        console.error('No data available or data format is incorrect');
                    }
                })
                .catch(error => {
                    console.error('Error fetching the clustering data:', error);
                });
        });

        function renderBarChart(anggotaData) {
            const ctx = document.getElementById('clusterChart').getContext('2d');

            // Filter data untuk cluster_0 dan cluster_1
            const cluster_0 = anggotaData.find(item => item.cluster === 'cluster_0');
            const cluster_1 = anggotaData.find(item => item.cluster === 'cluster_1');

            if (!cluster_0 || !cluster_1) {
                console.error('Data for cluster_0 or cluster_1 not found');
                return;
            }

            const labels = ['cluster_0', 'cluster_1'];
            const data = [cluster_0.anggota_cluster, cluster_1.anggota_cluster];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Anggota Klaster',
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderLineChart(clusterData) {
            const ctx = document.getElementById('silhouetteChart').getContext('2d');

            const epsValues = clusterData.map(item => item.eps);
            const minptsValues = clusterData.map(item => item.minpts);
            const silhouetteScores = clusterData.map(item => item.silhouette_index);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: epsValues.map((_, index) => `EPS ${epsValues[index]}, MINPTS ${minptsValues[index]}`),
                    datasets: [{
                        label: 'Silhouette Score',
                        data: silhouetteScores,
                        fill: false,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
@endpush
