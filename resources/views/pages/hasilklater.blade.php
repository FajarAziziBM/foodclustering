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
                                <br>
                                <h3 class="mb-0">Analisis kNNdist</h3>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                    <tr>
                                        <th> Eps </th>
                                        <th> minPts </th>
                                        <th> Jumlah cluster </th>
                                        <th> Jumlah Noise </th>
                                        <th class="text-right"> Jumlah Tercluster </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> 350 </td>
                                        <td> 3 </td>
                                        <td> 2 </td>
                                        <td> 0 </td>
                                        <td class="text-right"> 180 </td>
                                    </tr>
                                    <tr>
                                        <td> 400 </td>
                                        <td> 3 </td>
                                        <td> 2 </td>
                                        <td> 0 </td>
                                        <td class="text-right"> 180 </td>
                                    </tr>
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
                                <h3 class="mb-0">Analisis Silhouette Indek</h3>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                    <tr>
                                        <th> Eps </th>
                                        <th> minPts </th>
                                        <th class="text-right"> Silhouette Indek </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> 350 </td>
                                        <td> 3 </td>
                                        <td class="text-right"> 0,5 </td>
                                    </tr>
                                    <tr>
                                        <td> 400 </td>
                                        <td> 3 </td>
                                        <td class="text-right"> 0,7 </td>
                                    </tr>
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
                            <table class="table">
                                <thead class=" text-primary">
                                    <tr>
                                        <th> Eps </th>
                                        <th> minPts </th>
                                        <th> Jumlah cluster </th>
                                        <th> Jumlah Noise </th>
                                        <th class="text-right"> Jumlah Tercluster </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> 350 </td>
                                        <td> 3 </td>
                                        <td> 2 </td>
                                        <td> 0 </td>
                                        <td class="text-right"> 180 </td>
                                    </tr>
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

        });
    </script>
@endpush
