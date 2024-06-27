@extends('layouts.app', [
    'class' => 'Klastering Data',
    'elementActive' => 'klasteringdata'
])

@section('content')
    <div class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Klastering Data</h3>
                            </div>
                        <hr>
                    </div>

                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table table-striped" style="width:100%" id="dataTable">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                searching: false,
                scrollCollapse: true,
                ajax: {
                    url: "{{ route('klasteringdata') }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'tahun', name: 'tahun' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
            });

            $(document).on('click', '.btn-cluster', function(e) {
                e.preventDefault();
                var tahun = $(this).closest('tr').find('td:eq(0)').text();
                $.ajax({
                    url: "{{ route('sendDatas') }}",
                    type: 'GET',
                    data: {
                        tahun: tahun
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Data berhasil diklastering untuk tahun ' + tahun);
                            // Handle success, e.g., update UI or reload data
                        } else {
                            alert('Gagal melakukan klastering untuk tahun ' + tahun + ': ' + response.message);
                            // Handle failure scenario
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat melakukan klastering untuk tahun ' + tahun);
                        // Handle error scenario
                    }
                });
            });

            $(document).on('click', '.btn-danger', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var tahun = $(this).closest('tr').find('td:eq(0)').text();
                $.ajax({
                    url: "{{ route('delete.cluster', ':id') }}".replace(':id', id),
                    type: 'DELETE', // Use DELETE method for delete action
                    data: {
                        _token: "{{ csrf_token() }}", // CSRF token
                        tahun: tahun
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Data klaster berhasil dihapus untuk tahun ' + tahun);
                            // Handle success, e.g., update UI or reload data
                            table.ajax.reload(); // Reload data table
                        } else {
                            alert('Gagal menghapus data klaster untuk tahun ' + tahun + ': ' + response.message);
                            // Handle failure scenario
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus data klaster untuk tahun ' + tahun);
                        // Handle error scenario
                    }
                });
            });
        });
    </script>
@endpush
