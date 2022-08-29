@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Permisos</h1>
        <div class="lead">
            Gestión de permisos
        </div>

        <div class="mt-2">
            @include('layouts.partials.messages')
        </div>

        <div class="w-50 mx-auto mt-4">
            <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm float-right mb-3">Añadir permisos</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col" width="10%">Nombre</th>
                        <th scope="col" width="10%">Guard</th>
                        <th scope="col" colspan="3" width="1%">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->guard_name }}</td>
                            <td><a href="{{ route('permissions.edit', $permission->id) }}"
                                    class="btn btn-info btn-sm">Edit</a>
                            </td>
                            <td>
                                {!! Form::open([
                                    'method' => 'DELETE',
                                    'route' => ['permissions.destroy', $permission->id],
                                    'style' => 'display:inline',
                                ]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
