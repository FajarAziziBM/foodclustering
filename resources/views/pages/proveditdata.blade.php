@extends('layouts.app', [
    'class' => 'Edit Data',
    'elementActive' => 'inputdata',
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <div class="row align-items-center">
                            <hr>
                        </div>

                        <div class="card-body ">
                            <div class="container">
                                <form id="editForm" action="{{ route('update.province', ['id' => $province->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT') <!-- Use PUT method for updating data -->

                                    <div class="form-group">
                                        <label for="namaprovinsi">Nama Provinsi</label>
                                        <input type="text" name="namaprovinsi" id="namaprovinsi" class="form-control"
                                            value="{{ $province->namaprovinsi }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="luaspanen">Luas Panen</label>
                                        <input type="number" name="luaspanen" id="luaspanen" class="form-control"
                                            value="{{ $province->luaspanen }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="produktivitas">Produktivitas</label>
                                        <input type="number" name="produktivitas" id="produktivitas" class="form-control"
                                            value="{{ $province->produktivitas }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="produksi">Produksi</label>
                                        <input type="number" name="produksi" id="produksi" class="form-control"
                                            value="{{ $province->produksi }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="tahun">Tahun</label>
                                        <input type="number" name="tahun" id="tahun" class="form-control"
                                            value="{{ $province->tahun }}" required>
                                    </div>

                                    <div class="col-md-12 text-right">
                                        <a href="{{ route('edit.province', ['id' => $province->tahun])}}" class="btn btn-info btn-round">Kembali</a>
                                        <button type="submit" class="btn btn-info btn-round">Simpan Perubahan</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection

        @push('scripts')
            <script>
                $(document).ready(function() {
                    var provinceId = "{{ $province->id }}"; // Get the province ID

                    $('#editForm').on('submit', function(event) {
                        event.preventDefault(); // Prevent the form from submitting the traditional way

                        var formData = $(this).serialize(); // Serialize form data

                        $.ajax({
                            type: "POST",
                            url: "{{ route('update.province', ['id' => ':id']) }}".replace(':id',
                                provinceId),
                            data: formData,
                            success: function(data) {
                                console.log('Data updated successfully');
                                window.location.href = "/inputdata";
                            },
                            error: function(data) {
                                console.error('Error updating data');
                            }
                        });
                    });
                });
            </script>
        @endpush
