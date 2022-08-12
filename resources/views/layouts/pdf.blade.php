<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>RPC Bulacan ID</title>
    <meta name="description" content="RPC Bulacan ID">
    <title>{{ config('app.name', 'RPC Bulacan') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
        <link rel="shortcut icon" href="{{ asset('images/logo.ico') }}" />
    @livewireStyles
  </head>
  <body>
      <div id="app">
        @yield('content')
      </div>
      <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
        <script src="{{ asset('js/html2canvas.min.js') }}"></script>
      @livewireScripts
      <script type="text/javascript">
            document.addEventListener('livewire:load', function () {
                window.addEventListener('download-div', event => {
                    div_content = document.querySelector("#did");
                    html2canvas(div_content).then(function(canvas) {
                        data = canvas.toDataURL('image/jpeg');
                        Livewire.emit('initiateDownload', data, event.detail.flname);
                    });
                });
            });
      </script>
  </body>
</html>