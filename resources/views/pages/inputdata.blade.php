@extends('layouts.app', [
    'class' => 'Input Data',
    'elementActive' => 'inputdata',
])

@section('content')
    <div class="content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Upload Data</h3>
                            </div>
                        </div>
                        <hr>
                        <div class="row align-items-center">
                            <div class="col-8">
                                <form action="{{ route('importdatas') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" class="form-control-file" name="file" required>
                                    <small class="form-text text-muted">file harus ekstensi .csv</small>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="col-md-12">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="col-md-12">
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Datas</h3>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="card-body ">
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
                        <hr>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // Event delegation untuk menangani klik tombol delete
            document.getElementById('datastable').addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('btn-cluster')) {
                    event.preventDefault();
                    var tahun = event.target.closest('tr').querySelector('td:first-child').textContent
                        .trim();

                    // Konfirmasi pengguna sebelum menghapus
                    if (confirm('Anda yakin ingin menghapus data untuk tahun ' + tahun + '?')) {
                        var csrf_token = '{{ csrf_token() }}';

                        // Lakukan AJAX request untuk menghapus data
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', "{{ route('delete.province', ':tahun') }}".replace(':tahun',
                            tahun), true);
                        xhr.setRequestHeader('Content-Type', 'application/json');
                        xhr.setRequestHeader('X-CSRF-Token', csrf_token);
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                if (xhr.status === 200) {
                                    var response = JSON.parse(xhr.responseText);
                                    if (response.success) {
                                        // Refresh DataTable setelah berhasil menghapus
                                        var dataTable = $('#datastable').DataTable();
                                        dataTable.ajax.reload();

                                        // Memanggil fungsi showSuccessMessage
                                        showSuccessMessage('Data berhasil dihapus untuk tahun ' +
                                            tahun);
                                    } else {
                                        alert('Gagal melakukan hapus data untuk tahun ' + tahun + ': ' +
                                            response.message);
                                    }
                                } else {
                                    console.error('Terjadi kesalahan saat melakukan request:', xhr
                                        .status);
                                    alert('Terjadi kesalahan saat melakukan hapus data untuk tahun ' +
                                        tahun);
                                }
                            }
                        };
                        xhr.send(JSON.stringify({
                            tahun: tahun
                        }));
                    }
                }
            });
        });

        // Fungsi untuk menampilkan pesan sukses
        function showSuccessMessage(message) {
            alert(message);
        }
    </script>
@endpush
