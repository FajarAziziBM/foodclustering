@extends('layouts.app', [
    'class' => 'Landing Page',
    'elementActive' => 'landing-page'
])

@section('content')
    <div class="content col-md-12 ml-auto mr-auto">
        <div class="header py-5 pb-7 pt-lg-9">
            <div class="container col-md-10">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-8 pt-5">
                            <h1 >{{ __('Welcome to Food Clustering with DBSCAN Method.') }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

