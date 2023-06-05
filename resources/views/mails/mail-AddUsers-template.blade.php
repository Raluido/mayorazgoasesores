<h4>Listado de empresas para acceder al area de clientes</h4>

<div class="w-50 mx-auto mt-4">
    @if (!empty($usersNifPass))
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col" width="50%">Nif</th>
                    <th scope="col" width="50%">Contraseña</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usersNifPass as $userNifPass)
                    <tr>
                        <td>{{ $userNifPass['nif'] }}</td>
                        <td>{{ $userNifPass['password'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No se ha creado ninguna empresa nueva</p>
    @endif
</div>

<h4>Listado de empresas que han dado error</h4>

@if (empty($uploadError))
    <p>No han habido errores</p>
@endif
