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
                        <h5 class="card-title">Hasil Klatering</h5>
                        <hr>
                    </div>
                    <div class="card-body ">
                        <canvas id=chartHours width="400" height="100"></canvas>
                    </div>
                    <div class="card-footer ">
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
