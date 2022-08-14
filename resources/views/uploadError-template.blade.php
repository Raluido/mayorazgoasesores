<h4>Listado de nóminas subidas</h4>


<p>Las siguientes nóminas no corresponden al mes seleccionado y no se han subido:</p>
@if ($uploadError)
        @foreach ($uploadError as $index)
            <p>{{ $index }}</p>
        @endforeach

@endif
