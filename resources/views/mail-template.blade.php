<h4>Se ha creado una nueva empresa que no figuraba en la base de datos</h4>

<h5>Por favor edite los campos "nombre" y "email"</h5>

@if ($data)
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col" width="50%">Nif</th>
                <th scope="col" width="50%">Contraseña</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $data['nif'] }}</td>
                <td>{{ $data['password'] }}</td>
            </tr>
        </tbody>
    </table>
@endif
