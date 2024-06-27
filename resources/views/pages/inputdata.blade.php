@extends('layouts.app', [
    'class' => 'Input Data',
    'elementActive' => 'inputdata'
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

                    <div class="card-body ">
                        <div class="table-responsive">
                        <table class="table table-striped" style="width:100%" id="inputdatas">
                                    <thead class=" text-primary">
                                        <tr>
                                            <th> Provinsi </th>
                                            <th> Luas Panen(ha)</th>
                                            <th> Produktivitas(ku/ha)</th>
                                            <th> Produksi(ton)</th>
                                            <th> Tahun  </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                        </tr>
                                </table>
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
            var table = $('#inputdatas').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    scrollCollapse: true,
                    ajax: "{{ route('inputdata') }}",
                    columns: [
                        { data: 'namaprovinsi', name: 'namaprovinsi' },
                        { data: 'luaspanen', name: 'luaspanen' },
                        { data: 'produktivitas', name: 'produktivitas' },
                        { data: 'produksi', name: 'produksi' },
                        { data: 'tahun', name: 'tahun' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-right' }
                    ]
                });
            });


            function deleteData(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                $.ajax({
                    type: "POST", // Menggunakan metode POST
                    url: "{{ route('delete.province', ['province' => ':id']) }}".replace(':id', id),
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": "DELETE" // Menyertakan _method dengan nilai DELETE
                    },
                    success: function (data) {
                        console.log('Data berhasil dihapus');
                        // Refresh the DataTable after deleting the row
                        $('#inputdatas').DataTable().ajax.reload();
                    },
                    error: function (data) {
                        console.error('Gagal menghapus data');
                    }
                });
            }
        }
    </script>
@endpush


