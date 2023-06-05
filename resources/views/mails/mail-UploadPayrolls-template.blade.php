<h4>Listado de nóminas de {{ $monthInput}} {{ $yearInput }}</h4>

@if (empty($uploadError))
<p>No han habido errores</p>
@else
@foreach ($uploadError as $index)
<p>{{ $index }}</p>
@endforeach
@endif