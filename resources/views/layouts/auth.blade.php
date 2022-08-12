<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'RPC Bulacan - Administration Panel') }}</title>
        <link rel="stylesheet" href="{{ asset('admin/vendors/feather/feather.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/ti-icons/css/themify-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/css/vendor.bundle.base.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/ti-icons/css/themify-icons.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/js/select.dataTables.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/css/vertical-layout-light/style.css') }}">
        <link rel="shortcut icon" href="{{ asset('images/logo.ico') }}" />
        <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
        @livewireStyles
    </head>
    <body>
        <div class="container-scroller">
            @yield('content')
        </div>
        @livewireScripts
        <script src="{{ asset('admin/vendors/js/vendor.bundle.base.js') }}"></script>
        <script src="{{ asset('admin/vendors/chart.js/Chart.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/datatables.net/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('admin/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ asset('admin/js/dataTables.select.min.js') }}"></script>
        <script src="{{ asset('admin/js/off-canvas.js') }}"></script>
        <script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
        <script src="{{ asset('admin/js/template.js') }}"></script>
        <script src="{{ asset('admin/js/settings.js') }}"></script>
        <script src="{{ asset('admin/js/todolist.js') }}"></script>
        <script src="{{ asset('admin/js/dashboard.js') }}"></script>
        <script src="{{ asset('admin/js/Chart.roundedBarCharts.js') }}"></script>
        <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
        <script type="text/javascript">   
            window.addEventListener('userCreated', event => {
                swal({
                    title: "Congrats!",
                    text: "User registration successful.",
                    type: "success"
                }).then((result) => {
                    window.location = "{{ route('login') }}";
                });
            });
            window.addEventListener('userFailed', event => {
                swal("Ooopss!", event.detail.msg, "error");
            });
            window.addEventListener('email-not-found', event => {
                swal(event.detail.title, event.detail.msg, event.detail.type);
            });
            window.addEventListener('mismatch', event => {
                swal(event.detail.title, event.detail.msg, event.detail.type);
            });
            window.addEventListener('loginFailed', event => {
                swal("Ooopss!", event.detail.msg, "error");
            });
        </script>
    </body>
</html>