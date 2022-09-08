<h4>Listado de imputación de costes con errores</h4>

@if ($uploadError)
    @foreach ($uploadError as $index)
        <p>{{ $index }}</p>
    @endforeach
@endif
