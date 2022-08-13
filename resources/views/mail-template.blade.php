<h4>Bienvenido -- Estos son sus datos para loguearse</h4>

    @if ($data)

    @foreach ( $data as $dataa )
        <p>{{ $dataa }}</p>
    @endforeach

    @endif

