@extends('layouts.app', [
    'class' => 'Input Data',
    'elementActive' => 'inputdata'
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Province Data</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('edit.province', $province->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="namaprovinsi">Province Name</label>
                                <input type="text" class="form-control" id="namaprovinsi" name="namaprovinsi" value="{{ $province->namaprovinsi }}" required>
                            </div>
                            <div class="form-group">
                                <label for="luaspanen">Luas Panen (ha)</label>
                                <input type="number" class="form-control" id="luaspanen" name="luaspanen" value="{{ $province->luaspanen }}" required>
                            </div>
                            <div class="form-group">
                                <label for="produktivitas">Produktivitas (ku/ha)</label>
                                <input type="number" class="form-control" id="produktivitas" name="produktivitas" value="{{ $province->produktivitas }}" required>
                            </div>
                            <div class="form-group">
                                <label for="produksi">Produksi (ton)</label>
                                <input type="number" class="form-control" id="produksi" name="produksi" value="{{ $province->produksi }}" required>
                            </div>
                            <div class="form-group">
                                <label for="tahun">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" value="{{ $province->tahun }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
