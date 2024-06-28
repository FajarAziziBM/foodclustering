@extends('layouts.app', [
    'class' => 'Klastering Data',
    'elementActive' => 'klasteringdata'
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Klastering Data</h3>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="card-body">
                        @if (session('success_message'))
                            <div class="alert alert-success">
                                {{ session('success_message') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped" style="width:100%" id="dataTable">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Tahun</th>
                                        <th class="text-right">Action</th>
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
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Hapus Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data untuk tahun <span id="deleteYear"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
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
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-right' }
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
                            table.ajax.reload(); // Reload data table
                            showSuccessMessage('Data berhasil diklastering untuk tahun ' + tahun);
                        } else {
                            alert('Gagal melakukan klastering untuk tahun ' + tahun + ': ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat melakukan klastering untuk tahun ' + tahun);
                    }
                });
            });

            $(document).on('click', '.btn-danger', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var tahun = $(this).data('tahun');
                deleteData(id, tahun); // Call deleteData function with id and tahun
            });

            $('#confirmDeleteBtn').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('delete.cluster', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tahun: $('#deleteYear').text()
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Data klaster berhasil dihapus untuk tahun ' + $('#deleteYear').text());
                            $('#deleteConfirmationModal').modal('hide');
                            table.ajax.reload(); // Reload data table
                        } else {
                            alert('Gagal menghapus data klaster untuk tahun ' + $('#deleteYear').text() + ': ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus data klaster untuk tahun ' + $('#deleteYear').text());
                    }
                });
            });

            function showSuccessMessage(message) {
                $.ajax({
                    url: "{{ route('klasteringdata') }}",
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            sessionStorage.setItem('success_message', message);
                            window.location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menampilkan pesan sukses');
                    }
                });
            }

            var successMessage = sessionStorage.getItem('success_message');
            if (successMessage) {
                var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                alertHtml += successMessage;
                alertHtml += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                alertHtml += '<span aria-hidden="true">&times;</span></button></div>';

                $('.content').prepend(alertHtml);
                sessionStorage.removeItem('success_message');
            }
        });

        function deleteData(id, tahun) {
            $('#deleteYear').text(tahun);
            $('#deleteConfirmationModal').modal('show');
            $('#confirmDeleteBtn').data('id', id);
        }
    </script>
@endpush
