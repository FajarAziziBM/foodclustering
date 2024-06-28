<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="#" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="{{ asset('paper') }}/img/LogoPNLNew.png">
            </div>
        </a>
        <a href="/" class="simple-text logo-normal">
            {{ __('Food Clustering') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'dashboard') }}">
                    <i class="nc-icon nc-laptop"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'inputdata' || $elementActive == 'klasteringdata' || $elementActive == 'hasilklasterdbscan' ? 'active' : '' }}">
                <a data-toggle="collapse" aria-expanded="true" href="#laravelExamples">
                    <i class="nc-icon nc-chart-pie-36"></i>
                    <p>
                            {{ __('Food Clustering') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="laravelExamples">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'inputdata' ? 'active' : '' }}">
                            <a href="{{ route('page.index', 'inputdata') }}">
                                <i class="sidebar-mini-icon nc-icon nc-tile-56"></i>
                                <span class="sidebar-normal">{{ __(' Input Data ') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'klasteringdata' ? 'active' : '' }}">
                            <a href="{{ route('page.index', 'klasteringdata') }}">
                                <i class="sidebar-mini-icon nc-icon nc-tile-56"></i>
                                <span class="sidebar-normal">{{ __(' Klastering Data ') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'hasilklasterdbscan' ? 'active' : '' }}">
                            <a href="{{ route('page.index', 'hasilklasterdbscan') }}">
                                <i class="sidebar-mini-icon nc-icon nc-tile-56"></i>
                                <span class="sidebar-normal">{{ __(' Hasil Klaster ') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
