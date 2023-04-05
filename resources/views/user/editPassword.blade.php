@extends('layouts.app-master')

@section('content')
<div class="editPass">
    <div class="innerEditPass">
        <div class="top">
            <h1>Usuario</h1>
        </div>
        <div class="bottom">
            <div class="sideNavButtons">
                <button class="stylingButtons gray"><a class="buttonTextWt" href="{{ route('user.editData') }}">Modificar
                        datos</a>
                </button>
                <button class="stylingButtons gray"><a class="buttonTextWt" href="{{ route('user.editPassword') }}">Modificar
                        contraseña</a>
                </button>
            </div>
            <div class="divForm">
                <form method='POST' action="{{ route('user.updatePassword') }}">
                    @csrf
                    <div class='inputForm'>
                        <label for='password' required>Contraseña</label>
                        <input type='password' name='password' id='password' placeholder='Contraseña' value=''>
                        @if ($errors->has('password'))
                        <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="inputForm">
                        <label for='password_confirmation' required>Repite contraseña</label>
                        <input type='password' name='password_confirmation' id='password_confirmation' placeholder='Repite contraseña' value=''>
                        @if ($errors->has('password_confirmation'))
                        <span class="text-danger text-left">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('home.index') }}" class="buttonTextWt">Volver</a></button>
                        <button class="stylingButtons green buttonTextWt" type='submit'>Modificar contraseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection