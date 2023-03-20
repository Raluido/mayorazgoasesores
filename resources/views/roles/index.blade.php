@extends('layouts.app-master')

@section('content')
<section class="roles">
    <div class="innerRoles">
        <div class="top">
            <div class="">
                <h1>Roles</h1>
            </div>
            <div class="">
                Gestión de roles
            </div>
            <div class="messages">
                @include('layouts.partials.messages')
            </div>
        </div>
        <div class="bottom">
            <div class="addRol">
                <button class="green stylingButtons"><a href="{{ route('roles.create') }}" class="buttonTextWt">Añadir rol</a></button>
            </div>
            <table class="">
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
                        <button class="green stylingButtons"><a class="buttonTextWt" href="{{ route('roles.show', $role->id) }}">Mostrar</a></button>
                    </td>
                    <td>
                        <button class="blue stylingButtons"><a class="buttonTextWt" href="{{ route('roles.edit', $role->id) }}">Editar</a></button>
                    </td>
                    <td>
                        {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                        {!! Form::submit('Eliminar', ['class' => 'red stylingButtons buttonTextWt']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="">
            {!! $roles->links() !!}
        </div>
        <div class="backButton">
            <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
        </div>
    </div>
</section>
@endsection