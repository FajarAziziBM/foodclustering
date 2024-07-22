@extends('layouts.app', [
    'class' => 'Klastering Data',
    'elementActive' => 'klasteringdata',
])

@section('content')
    <div class="content">

        <div class="row">

            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <br>
                                <h3 class="mb-0">Analisis Percobaan EPS dan MINPTS Yang Bervariasi</h3>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table table-striped" style="width:100%" id="dataTable1">
                                <thead class=" text-primary">
                                    <tr>
                                        <th> Eps </th>
                                        <th> minPts </th>
                                        <th> Jumlah cluster </th>
                                        <th> Jumlah Noise </th>
                                        <th> Jumlah Terkluster </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <br>
                                <h3 class="mb-0">Analisis Nilai Silhouette</h3>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table table-striped" style="width:100%" id="dataTable2">
                                <thead class=" text-primary">
                                    <tr>
                                        <th> Eps </th>
                                        <th> minPts </th>
                                        <th> Silhouette Indek </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <br>
                                <h3 class="mb-0">Hasil Klaster Terbaik</h3>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="card-body ">
                        <div class="table-responsive">
                            <table id="dataTable3" class="table table-striped" style="width:100%">
                                <thead class=" text-primary">
                                    <tr>
                                        <th> Eps </th>
                                        <th> minPts </th>
                                        <th> Jumlah cluster </th>
                                        <th> Jumlah Noise </th>
                                        <th> Jumlah Terkluster </th>
                                        <th> Silhouette Indek </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <hr>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table1 = $('#dataTable1').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                scrollCollapse: true,
                ajax: {
                    url: "{{ route('hasilklaster', ['id' => $idku]) }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'eps',
                        name: 'eps'
                    },
                    {
                        data: 'minpts',
                        name: 'minpts'
                    },
                    {
                        data: 'jmlcluster',
                        name: 'jmlcluster'
                    },
                    {
                        data: 'jmlnoice',
                        name: 'jmlnoice'
                    },
                    {
                        data: 'jmltercluster',
                        name: 'jmltercluster'
                    }
                ],
            });
        });

        $(document).ready(function() {
            var table2 = $('#dataTable2').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                scrollCollapse: true,
                ajax: {
                    url: "{{ route('hasilklaster', ['id' => $idku]) }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'eps',
                        name: 'eps'
                    },
                    {
                        data: 'minpts',
                        name: 'minpts'
                    },
                    {
                        data: 'silhouette_index',
                        name: 'silhouette_index'
                    }
                ],
            });
        });

        $(document).ready(function() {
            var table3 = $('#dataTable3').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                scrollCollapse: true,
                ajax: {
                    url: "{{ route('hasilklaster', ['id' => $idku, 'type' => 'datas1']) }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'eps',
                        name: 'eps'
                    },
                    {
                        data: 'minpts',
                        name: 'minpts'
                    },
                    {
                        data: 'jmlcluster',
                        name: 'jmlcluster'
                    },
                    {
                        data: 'jmlnoice',
                        name: 'jmlnoice'
                    },
                    {
                        data: 'jmltercluster',
                        name: 'jmltercluster'
                    },
                    {
                        data: 'silhouette_index',
                        name: 'silhouette_index'
                    }
                ],
            });
        });
    </script>
@endpush
