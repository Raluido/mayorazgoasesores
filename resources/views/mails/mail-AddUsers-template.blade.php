<h4>Listado de empresas para acceder al area de clientes</h4>

<div class="w-50 mx-auto mt-4">
    @if ($usersNifPass)
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
    @endif
</div>

<h4>Listado de empresas que han dado error</h4>

@if ($uploadError)
    @foreach ($uploadError as $index)
        <p>{{ $index }}</p>
    @endforeach
@endif
