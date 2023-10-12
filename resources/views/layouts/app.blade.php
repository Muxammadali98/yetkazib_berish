<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.head')
        @livewireStyles
        @yield('styles')
    </head>

    <body>
        @include('modals.vertically-centered')
        <div class="loader"></div>
        <div id="app">
            <div class="main-wrapper main-wrapper-1">
                @include('layouts.navbar')
                @include('layouts.sidebar')
                <div class="main-content">
                    @yield('content')
                </div>
                <footer class="main-footer">
                    @include('layouts.footer')
                </footer>
            </div>
        </div>
        <!-- General JS Scripts -->
        <script src="/assets/js/app.min.js"></script>
        <!-- JS Libraies -->
        <script src="/assets/bundles/apexcharts/apexcharts.min.js"></script>
        <!-- Page Specific JS File -->
        <script src="/assets/js/page/index.js"></script>
        <!-- Template JS File -->
        <script src="/assets/js/scripts.js"></script>
        <!-- Custom JS File -->
        <script src="/assets/js/custom.js"></script>
        @livewireScripts
        @yield('js')
    </body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>
