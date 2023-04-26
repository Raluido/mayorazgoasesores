@extends('layouts.app-master')

@section('content')
<section class="permissions">
    <div class="innerPermissions paddingFix">
        <div class="top">
            <h1>Permisos</h1>
            Gestión de permisos
            <div class="mt-2">
                @include('layouts.partials.messages')
            </div>
        </div>
        <div class="bottom">
            <div class="addPermission">
                <button class="stylingButtons green"><a href="{{ route('permissions.create') }}" class="buttonTextWt">Añadir permisos</a></button>
            </div>
            <table class="">
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
                        <td><button class="stylingButtons green"><a href="{{ route('permissions.edit', $permission->id) }}" class="buttonTextWt">Editar</a></button>
                            {!! Form::open([
                            'method' => 'DELETE',
                            'route' => ['permissions.destroy', $permission->id],
                            'style' => 'display:inline',
                            ]) !!}
                            {!! Form::submit('Borrar', ['class' => 'buttonTextWt stylingButtons red']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
        </div>
    </div>
    @endsection