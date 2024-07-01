@extends('layouts.app', [
    'class' => 'Hasil Klaster',
    'elementActive' => 'hasilklasterdbscan',
])

@section('content')
    <div class="content">
        <div class="row d-flex justify-content-end">
            <div class="float-end col-2">
                <form>
                    <div class="input-group no-border p-3">
                        <select class="form-control" name="" id="">
                            <option value="">
                                2018
                            </option>
                            <option value="">
                                2019
                            </option>
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="nc-icon nc-zoom-split"></i>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-title">Cluster 2018</h5>
                        <hr>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" style="width:100%" id="dataTable">
                                <thead class=" text-primary">
                                    <tr>
                                        <th> Cluster </th>
                                        <th class="text-right"> Anggota Cluster </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Cluster Statistic</h5>
                        <hr>
                    </div>
                    <div class="card-body ">
                        <canvas id="chartCluster"></canvas>
                    </div>
                    <div class="card-footer ">
                        <div class="legend">

                        </div>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-title">Analisis Klaster</h5>
                        {{-- <p class="card-category">Peningkatan pertahun</p> --}}
                    </div>
                    <div class="card-body">
                        <canvas id="speedChart" width="400" height="100"></canvas>
                    </div>
                    <div class="card-footer">
                        <div class="chart-legend">
                            <i class="fa fa-circle text-info"></i> Now
                            <i class="fa fa-circle text-warning"></i> Before
                        </div>
                        <hr />
                        <div class="card-stats">
                            <i class="fa fa-check"></i> Data information certified
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                paging: false,
                searching: false,
                scrollCollapse: true,
                ajax: {
                    url: "{{ route('hasilklasterdbscan') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'cluster',
                        name: 'cluster'
                    },
                    {
                        data: 'anggota_cluster',
                        name: 'anggota_cluster',
                        className: 'text-right'
                    },
                ],
            });

            // Function to update chart based on DataTable data
            function updateChart(data) {
                var clusterLabels = [];
                var anggotaClusterData = [];

                data.forEach(function(item) {
                    clusterLabels.push(item.cluster);
                    anggotaClusterData.push(item.anggota_cluster.split(',')
                        .length); // Assuming anggota_cluster is a comma-separated list
                });

                var ctx = document.getElementById('chartCluster').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: clusterLabels,
                        datasets: [{
                            label: 'Cluster Data',
                            data: anggotaClusterData,
                            backgroundColor: [
                                '#e3e3e3',
                                '#4acccd',
                                '#fcc468',
                                '#ef8157'
                            ],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        legend: {
                            display: true,
                            position: 'right',
                        },
                        tooltips: {
                            enabled: true,
                        }
                    }
                });
            }

            // Fetch DataTable data and update chart
            table.on('xhr', function() {
                var data = table.ajax.json().data;
                updateChart(data);
            });

            // Initial chart update on page load
            updateChart([]);

        });

        $(document).ready(function() {
            var speedCanvas = document.getElementById("speedChart").getContext('2d');

            var dataFirst = {
                data: [0, 19, 15, 20, 30, 40, 40, 50, 25, 30, 50, 70],
                fill: false,
                borderColor: '#fbc658',
                backgroundColor: 'transparent',
                pointBorderColor: '#fbc658',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8,
            };

            var dataSecond = {
                data: [0, 5, 10, 12, 20, 27, 30, 34, 42, 45, 55, 63],
                fill: false,
                borderColor: '#51CACF',
                backgroundColor: 'transparent',
                pointBorderColor: '#51CACF',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8
            };

            var speedData = {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [dataFirst, dataSecond]
            };

            var chartOptions = {
                legend: {
                    display: true,
                    position: 'top',
                }
            };

            var lineChart = new Chart(speedCanvas, {
                type: 'line',
                data: speedData,
                options: chartOptions
            });
        });
    </script>
@endpush
