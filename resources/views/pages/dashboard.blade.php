@extends('layouts.app', [
    'class' => 'dashboard',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-world-2 text-success"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Data</p>
                                        <p class="card-title">{{ $prov }} Prov</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-refresh"></i> Update
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-cloud-download-93 text-success"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Luas Panen (ha)</p>
                                        <p class="card-title">{{ $luaspanen }} (ha)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-calendar-o"></i> Tahun: {{ $tahun }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-cloud-download-93 text-success"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Produktivitas (ku/ha)</p>
                                        <p class="card-title">{{ $produktivitas }} (ku/ha)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-calendar-o"></i> Tahun: {{ $tahun }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5 col-md-4">
                                    <div class="icon-big text-center icon-warning">
                                        <i class="nc-icon nc-cloud-download-93 text-success"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-md-8">
                                    <div class="numbers">
                                        <p class="card-category">Produksi (ton)</p>
                                        <p class="card-title">{{ $produksi }} (ton)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-calendar-o"></i> Tahun: {{ $tahun }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Grafik Klaster</h5>
                            <hr>
                            <p class="card-category">Hasil Terbaik</p>
                        </div>
                        <div class="card-body">
                            <canvas id="clusterChart"></canvas>
                        </div>
                        <div class="card-footer">
                            <hr>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h5 class="card-title">Analisis Nilai Silhouette</h5>
                            <hr>
                        </div>
                        <div class="card-body">
                            <canvas id="silhouetteChart"></canvas>
                        </div>
                        <div class="card-footer">
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var jumlahAnggota = {{ $jumlah_anggota }};
        var jumlahAnggota2 = {{ $jumlah_anggota2 }};
        var clusterData = {!! json_encode($datasgraf['clusterData']) !!};

        // Render doughnut chart function
        function renderDoughnutChart(jumlah_anggota, jumlah_anggota2) {
            const ctx = document.getElementById('clusterChart').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cluster 1', 'Cluster 0'],
                    datasets: [{
                        label: 'Jumlah Anggota Klaster',
                        data: [jumlah_anggota, jumlah_anggota2],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        }

        // Render line chart function
        function renderLineChart(clusterData) {
            const ctx = document.getElementById('silhouetteChart').getContext('2d');

            const epsValues = clusterData.map(item => `EPS ${item.eps}`);
            const silhouetteScores = clusterData.map(item => item.silhouette_index);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: epsValues,
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

        // Call render functions
        renderDoughnutChart(jumlahAnggota, jumlahAnggota2);
        renderLineChart(clusterData);
    </script>
@endpush
