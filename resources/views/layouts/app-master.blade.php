<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Websiwebs">
    <title>mayorazgo asesores</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('storage/design/myriadFont/style.css') }}" />

</head>

<body>

    @include('layouts.partials.navbar')

    <main class="">
        @yield('content')
    </main>

    <script src="{{ asset('js/jquery-3.6.4.min.js') }}">
    </script>
    <script src="{{ asset('js/mobileMenu.js') }}"></script>
    @section('scripts')
    @show
    @yield('js')
</body>

</html>