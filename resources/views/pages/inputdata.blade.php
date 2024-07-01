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
                                <h3 class="mb-0">Datas</h3>
                            </div>
                            <div class="col-4 text-right">
                                <form action="{{ route('importdatas') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="file" required>
                                    <button class="btn btn-sm btn-primary">
                                        Input Data
                                    </button>
                                </form>
                            </div>
                            <hr>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" style="width:100%" id="datastable">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Tahun</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endsection

        @push('scripts')
            <script>
                $(document).ready(function() {
                    var table = $('#datastable').DataTable({
                        processing: true,
                        serverSide: true,
                        paging: true,
                        searching: false,
                        scrollCollapse: true,
                        ajax: {
                            url: "{{ route('inputdata') }}",
                            type: 'GET'
                        },
                        columns: [{
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
                        ],
                    });
                });

                function deleteData(id) {
                    if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                        $.ajax({
                            type: "DELETE", // Menggunakan metode POST
                            url: "{{ route('delete.province', ['province' => ':id']) }}".replace(':id', id),
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "DELETE" // Menyertakan _method dengan nilai DELETE
                            },
                            success: function(data) {
                                console.log('Data berhasil dihapus');
                                // Refresh the DataTable after deleting the row
                                $('#inputdatas').DataTable().ajax.reload();
                            },
                            error: function(data) {
                                console.error('Gagal menghapus data');
                            }
                        });
                    }
                }
            </script>
        @endpush
