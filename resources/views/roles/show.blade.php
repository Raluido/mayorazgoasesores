@extends('layouts.app-master')

@section('content')
<section class="showRoles">
    <div class="innerShowRoles">
        <div class="top">
            <h1>Role "{{ ucfirst($role->name) }}"</h1>
            <h3>Asignar permisos</h3>
        </div>
        <div class="bottom">
            <table class="">
                <thead>
                    <th scope="col" width="20%">Nombre</th>
                    <th scope="col" width="1%">Guard</th>
                </thead>

                @foreach ($rolePermissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->guard_name }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a href="{{ route('roles.index') }}" class="buttonTextWt">Volver</a></button>
            <button class="stylingButtons green"><a href="{{ route('roles.edit', $role->id) }}" class="buttonTextWt">Editar</a></button>
        </div>
    </div>
</section>
@endsection