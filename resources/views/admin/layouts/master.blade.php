@include('admin.layouts.__header')

<body>
    <script src="{{ asset('assets/admin/static/js/initTheme.js') }}"></script>

    <div id="app">
        {{-- START SIDEBAR --}}
        @include('admin.layouts.__sidebar')
        {{-- END SIDEBAR --}}
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            {{-- START CONTENT --}}
            @yield('content')
            {{-- END CONTENT --}}

            {{-- START FOOTER --}}
            @include('admin.layouts.__footer')
            {{-- END FOOTER --}}
        </div>
    </div>

    <script src="{{ asset('assets/admin/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/admin/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>


    <script src="{{ asset('assets/admin/compiled/js/app.js') }}"></script>

    <!-- Need: Apexcharts -->
    <script src="{{ asset('assets/admin/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/static/js/pages/dashboard.js') }}"></script>

    @yield('script')

</body>

</html>
