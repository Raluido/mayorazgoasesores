@extends('layouts.app-master')

@section('content')
    <div class="mt-4">
        <h1>Roles</h1>
        <div class="lead">
            Gestión de roles
        </div>

        <div class="mt-2">
            @include('layouts.partials.messages')
        </div>
        <div class="w-50 mx-auto mt-4">
            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm float-right mb-3">Añadir rol</a>
            <table class="table table-bordered">
                <tr>
                    <th width="1%">No</th>
                    <th>Nombre</th>
                    <th width="3%" colspan="3">Acción</th>
                </tr>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ route('roles.show', $role->id) }}">Mostrar</a>
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('roles.edit', $role->id) }}">Editar</a>
                        </td>
                        <td>
                            {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                            {!! Form::submit('Eliminar', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="d-flex">
            {!! $roles->links() !!}
        </div>

    </div>
@endsection
