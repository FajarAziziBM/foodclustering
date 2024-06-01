@extends('layouts.app', [
'class' => 'Hasil Klaster',
'elementActive' => 'hasilklaster'
])

@section('content')
<div class="content">
    <div class="row d-flex justify-content-end">
        <div class="float-end col-2">
            <form >
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
                        <table class="table">
                            <thead class=" text-primary">
                                <tr>
                                    <th> Cluster </th>
                                    <th class="text-right"> Anggota Cluster </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> tahan </td>
                                    <td class="text-right"> Aceh, Sumatera Utara, Sumatera Barat </td>
                                </tr>
                                <tr>
                                    <td> Sedang </td>
                                    <td class="text-right"> Jakarta, Bengkulu, Riau </td>
                                </tr>
                                <tr>
                                    <td> rentan </td>
                                    <td class="text-right"> Kalimantan Barat, Kalimantan Utara, Sulawasi Selatan </td>
                                </tr>
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
                    <canvas id="chartEmail"></canvas>
                </div>
                <div class="card-footer ">
                    <div class="legend">
                        <i class="fa fa-circle text-success"></i> Tahan
                        <i class="fa fa-circle text-warning"></i> Sedang
                        <i class="fa fa-circle text-danger"></i> Rentan
                    </div>
                    <hr>
                </div>
            </div>
        </div>

    </div>
    @endsection

    @push('scripts')
    <script>
        $(document).ready(function() {
        ctx = document.getElementById('chartEmail').getContext("2d");
        myChart = new Chart(ctx, {
        type: 'pie',
            data: {
                labels: [1, 2, 3],
                datasets: [{
                    label: "Emails",
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    backgroundColor: [
                        '#e3e3e3',
                        '#4acccd',
                        '#fcc468',
                        '#ef8157'
                    ],
                    borderWidth: 0,
                    data: [342, 480, 530, 120]
                }]
            },

            options: {

                legend: {
                    display: false
                },

                pieceLabel: {
                    render: 'percentage',
                    fontColor: ['white'],
                    precision: 2
                },

                tooltips: {
                    enabled: false
                },

                scales: {
                    yAxes: [{

                        ticks: {
                            display: false
                        },
                        gridLines: {
                            drawBorder: false,
                            zeroLineColor: "transparent",
                            color: 'rgba(255,255,255,0.05)'
                        }

                    }],

                    xAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(255,255,255,0.1)',
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            display: false,
                        }
                    }]
                },
            }
        });
        });
    </script>
    @endpush
