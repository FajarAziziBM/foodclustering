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
                        <form action="{{ route('hasilklasterdbscan.show') }}" method="GET" id="tahunForm">
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
                    <div class="row">
                        <div class="card-header">

                            <h3 class="card-title" id="clusterTitle">Cluster {{ $selectedYear }}</h3>
                            <hr>

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
            </div>
        </div>

        <div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card ">
                            <div class="card-header ">
                                <h5 class="card-title">Cluster Statistic</h5>
                                <p class="card-category">Hasil Percobaan</p>
                            </div>
                            <div class="card-body ">
                                <canvas id="chartEmail"></canvas>
                            </div>
                            <div class="card-footer ">
                                <div class="legend">
                                    <i class="fa fa-circle text-primary"></i> Jumlah Cluster
                                    <i class="fa fa-circle text-warning"></i> Jumlah Noice
                                    <i class="fa fa-circle text-danger"></i> Eps
                                    <i class="fa fa-circle text-gray"></i> minPts
                                </div>
                                <hr>
                                <div class="stats">
                                    <i class="fa fa-calendar"></i> Number of emails sent
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
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

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function submitForm() {
            document.getElementById("tahunForm").submit();
        }
    </script>
@endpush
