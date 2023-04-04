@extends('layouts.app-master')

@section('content')
<div class="editUser">
    <div class="innerEditUser">
        <div class="top">
            <h2>Usuario</h2>
            Aqui puedes modificar tus datos de usuario y la contraseña
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <div class="sideNavButtons">
                    <button class="stylingButtons gray"><a class="buttonTextWt" href="{{ route('user.editData') }}">Modificar
                            datos</a>
                    </button>
                    <button class="stylingButtons gray"><a class="buttonTextWt" href="{{ route('user.editPassword') }}">Modificar
                            contraseña</a>
                    </button>
                </div>
                <div class="divForm">
                    <form method='POST' action='/user/updateData'>
                        @csrf
                        <div class="inputForm">
                            <label for='text' required>Nombre</label>
                            <input type='name' id='name' name='name' placeholder='Nombre' value='{{ $user->name }}'>
                            @if ($errors->has('name'))
                            <span class='text-danger text-left'>{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class='inputForm'>
                            <label for='email' required>Email</label>
                            <input type='email' name='email' id='email' placeholder='Email' value='{{ $user->email }}'>
                            @if ($errors->has('email'))
                            <span class='text-danger text-left'>{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class=''>
                            <input type='hidden' value='{{ $user->password }}'>
                        </div>
                        <div class="buttonsNav">
                            <button class="stylingButtons blue"><a href="{{ route('home.index') }}" class="buttonTextWt">Volver</a></button>
                            <button class="stylingButtons green buttonTextWt" type='submit'>Editar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection