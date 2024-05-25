@extends('layouts.app', [
    'class' => '',
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
                                    <button class="btn btn-sm btn-primary"> Input Data
                                        </button>
                                </form>
                            </div>
                        <hr>
                    </div>

                    <div class="card-body ">
                        <div class="table-responsive">
                        <table class="table" id="inputdatas">
                                    <thead class=" text-primary">
                                        <tr>
                                            <th> Provinsi </th>
                                            <th> Luas Panen(ha)</th>
                                            <th> Produktivitas(ku/ha)</th>
                                            <th> Produksi(ton)</th>
                                            {{-- <th> Tahun  </th> --}}
                                            <th class="text-right"> Action </th>
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
                ajax: {
                    url: "{{ route('inputdata') }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'namaprovinsi', name: 'provinsi' },
                    { data: 'luaspanen', name: 'luas_panen' },
                    { data: 'produktivitas', name: 'produktivitas' },
                    { data: 'produksi', name: 'produksi' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
                });
            });
    </script>
@endpush


