<h4>Listado de imputación de costes de {{ $monthInput }} {{ $yearInput }}</h4>

@if (empty($uploadError))
<p>No ha habido errores</p>
@else
@foreach ($uploadError as $index)
<p>{{ $index }}</p>
@endforeach
@endif