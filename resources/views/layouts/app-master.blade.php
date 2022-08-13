<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Websiwebs">
    <title>mayorazgo asesores</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>

<body>

    @include('layouts.partials.navbar')

    <main class="container mt-1">
        @yield('content')
    </main>


    <script src="{{ asset('js/app.js') }}" defer></script>
    @section('scripts')
    @show
    @yield('js')
</body>

</html>
