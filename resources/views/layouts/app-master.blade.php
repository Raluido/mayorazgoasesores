<!doctype html>
<html lang="es">

<head>
    <title>mayorazgo asesores</title>
    <meta name="author" content="Websiwebs.es">
    <meta charset="utf-8">
    <meta name="description" content="Asesoria laboral en La Orotava, Tenerife">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="Seguros sociales, nóminas, asesoramiento laboral, contratos, bonificaciones, despidos, finiquitos, altas y bajas por enfermedad y accidentes de trabajo">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('storage/design/myriadFont/style.css') }}" />

</head>

<body>

    @include('layouts.partials.navbar')

    <main class="">
        <iframe width="100%" height="100%" frameborder="0" id="bcw_iframe" scroll-top="yes" src="https://mayorazgoasesores.clientlink.es" instance="es" lang="es"></iframe>
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    <script src="{{ asset('js/jquery-3.6.4.min.js') }}">
    </script>
    <script src="{{ asset('js/mobileMenu.js') }}"></script>
    <script defer data-key="c38a2b6a-dfd8-4955-a86a-ca25d796edbd" src="https://widget.tochat.be/lefebvre/bundle.js"></script>
    @section('scripts')
    @show
    @yield('js')
</body>

</html>