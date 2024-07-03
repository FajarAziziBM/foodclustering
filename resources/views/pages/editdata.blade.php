@extends('layouts.app', [
    'class' => 'Input Data',
    'elementActive' => 'inputdata',
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
                                <h3 class="mb-0">Datas</h3>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table table-striped" style="width:100%" id="editdatas">
                                <thead class=" text-primary">
                                    <tr>
                                        <th> Provinsi </th>
                                        <th> Luas Panen(ha)</th>
                                        <th> Produktivitas(ku/ha)</th>
                                        <th> Produksi(ton)</th>
                                        <th> Tahun </th>
                                        <th class="text-right"> Action </th>
                                    </tr>
                                </thead>
                                </tr>
                            </table>
                        </div>
                        <hr>
                        <div class="col-md-12 text-right">
                            <a href="{{ route('inputdata') }}" class="btn btn-info btn-round">Kembali</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $('#editdatas').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    scrollCollapse: true,
                    ajax: "{{ route('edit.province', $id) }}",
                    columns: [{
                            data: 'namaprovinsi',
                            name: 'namaprovinsi'
                        },
                        {
                            data: 'luaspanen',
                            name: 'luaspanen'
                        },
                        {
                            data: 'produktivitas',
                            name: 'produktivitas'
                        },
                        {
                            data: 'produksi',
                            name: 'produksi'
                        },
                        {
                            data: 'tahun',
                            name: 'tahun'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-right'
                        }
                    ]
                });
            });
        </script>
    @endpush
