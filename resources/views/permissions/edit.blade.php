@extends('layouts.app-master')

@section('content')
<div class="editPermissions">
    <div class="innerEditPermissions">
        <div class="top">
            <h1>Editar permisos</h1>
            <h3 class="">Edición de permisos</h3>
        </div>
        <div class="bottom">
            <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
                @method('patch')
                @csrf
                <div class="">
                    <label for="name" class="" style="margin-right:3em;">Nombre</label>
                    <input value="{{ $permission->name }}" type="text" class="" name="name" placeholder="Name" required>

                    @if ($errors->has('name'))
                    <span class="">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </form>
            <div class="buttonsNav">
                <button class="stylingButtons blue"><a href="{{ route('permissions.index') }}" class="buttonTextWt">Volver</a></button>
                <button type="submit" class="stylingButtons green buttonTextWt">Guardar permisos</button>
            </div>
        </div>
    </div>
</div>
@endsection