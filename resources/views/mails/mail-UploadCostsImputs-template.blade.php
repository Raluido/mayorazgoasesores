<h4>Listado de imputación de costes de {{ $monthInput }} {{ $yearInput }} con errores</h4>

@if (empty($uploadError))
<p>No ha habido errores</p>
@else
@foreach ($uploadError as $index)
<p>{{ $index }}</p>
@endforeach
@endif


<h4>Listado de empresas creadas</h4>

<div class="w-50 mx-auto mt-4">
    @if (empty($usersCreated))
    <p>No se creó ninguna empresa nueva</p>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col" width="50%">Nif</th>
                <th scope="col" width="50%">Contraseña</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usersCreated as $index)
            <tr>
                <td>{{ $index['nif'] }}</td>
                <td>{{ $index['password'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>