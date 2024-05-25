@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'map'
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        Google Maps
                    </div>
                    <div class="card-body ">
                        <div id="map" class="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // demo.initGoogleMaps();
            let map;
            async function initMap() {
            const { Map } = await google.maps.importLibrary("maps");

            map = new Map(document.getElementById("map"), {
            center: { lat: -34.397, lng: 150.644 },
            zoom: 8,
        });
        }
        initMap();
        });
    </script>
@endpush