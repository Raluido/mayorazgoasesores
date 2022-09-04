@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Empresas</h1>
        <div class="d-flex justify-content-end">
            <div class="">
                <button class="btn btn-primary me-5"><a href="{{ route('users.create') }}"
                        class="text-decoration-none text-white">Añadir nueva empresa</a>
            </div>
            <div class=""><button class="btn btn-danger"><a class="text-decoration-none text-white"
                        href="{{ url('users/deleteAll') }}">Eliminar
                        todas</a></button>
            </div>
        </div>

        <div class="mt-2">
            @include('layouts.partials.messages')
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col" width="1%">#</th>
                    <th scope="col" width="15%">Empresa</th>
                    <th scope="col" width="15%">Nif</th>
                    <th scope="col" width="15%">Email</th>
                    <th scope="col" width="10%">Rol</th>
                    <th scope="col" width="1%" colspan="3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->nif }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach ($user->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td><a href="{{ route('users.show', $user->id) }}" class="btn btn-warning btn-sm">Show</a></td>
                        <td><a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm">Edit</a></td>
                        <td>
                            {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'style' => 'display:inline']) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            <button class="btn btn-secondary"><a href="{{ route('home.index') }}"
                    class="text-decoration-none text-white">Volver</a></button>
        </div>
        <div class="d-flex mb-5">
            {!! $users->links() !!}
        </div>

    </div>
@endsection
