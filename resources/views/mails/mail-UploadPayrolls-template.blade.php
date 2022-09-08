<h4>Listado de nóminas con errores</h4>

@if ($uploadError)
    @foreach ($uploadError as $index)
        <p>{{ $index }}</p>
    @endforeach
@endif
