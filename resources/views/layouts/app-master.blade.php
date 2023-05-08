<!doctype html>
<html lang="es">

<head>
    <title>mayorazgo asesores</title>
    <meta name="author" content="Websiwebs.es">
    <meta charset="utf-8">
    <meta name="description" content="Asesoria laboral en Tenerife">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="Seguros sociales, nóminas, asesoramiento laboral, contratos, bonificaciones, despidos, finiquitos, altas y bajas por enfermedad y accidentes de trabajo">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('storage/design/myriadFont/style.css') }}" />

</head>

<body>

    @include('layouts.partials.navbar')

    <main class="">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    <script src="{{ asset('js/jquery-3.6.4.min.js') }}">
    </script>
    <script src="{{ asset('js/mobileMenu.js') }}"></script>
    @section('scripts')
    @show
    @yield('js')
</body>

</html>