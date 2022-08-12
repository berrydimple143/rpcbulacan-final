<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'RPC Bulacan') }}</title>
        <link rel='stylesheet' href="{{ asset('css/main.css') }}" type='text/css' media='all' />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
        <link rel="shortcut icon" href="{{ asset('images/logo.ico') }}" />
        <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
        @livewireStyles
    </head>
    <body>
        <div id="app">
            @yield('content')
        </div>
        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
        <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
        <script type="text/javascript">
            document.addEventListener('livewire:load', function () {
                window.addEventListener('user-create', event => {
                    swal({
                        position: 'top-start',
                        title: event.detail.title, 
                        text: event.detail.msg, 
                        showCancelButton: true,
                        customClass: 'swal-size',
                        confirmButtonClass: "btn-success"
                    }).then((result) => {
                        if(result.value) {
                            window.livewire.emit('save', event.detail.info);
                        }
                    });
                });
                window.addEventListener('id-exist', event => {
                    swal({
                        title: event.detail.title, 
                        text: event.detail.msg, 
                        type: event.detail.type
                    }).then((result) => {
                        if(result.value) {
                            window.location = event.detail.rt;
                        }
                    });
                });
                window.addEventListener('user-exist', event => {
                    swal({
                        title: event.detail.title, 
                        text: event.detail.msg, 
                        type: event.detail.type,
                        showCancelButton: true,
                        confirmButtonClass: "btn-success"
                    }).then((result) => {
                        if(result.value) {
                            window.livewire.emit('save', event.detail.info);
                        } else {
                            window.location = event.detail.rt;
                        }
                    });
                });
                window.addEventListener('user-limit', event => {
                    swal({
                        title: event.detail.title, 
                        text: event.detail.msg, 
                        type: event.detail.type
                    }).then((result) => {
                        if(result.value) {
                            window.location = event.detail.rt;
                        }
                    });
                });
                window.addEventListener('user-saved', event => {
                    swal(event.detail.title, event.detail.msg, event.detail.type);
                    Livewire.emit('download', event.detail.info);
                });
                window.addEventListener('user-failed', event => {
                    swal(event.detail.title, event.detail.msg, event.detail.type);
                });
            });
        </script>
    </body>
</html>