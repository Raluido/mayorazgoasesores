@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <div class="">
            <h1>Usuario</h1>
        </div>
        <div class="container mt-4">
            <div class="d-flex">
                <div class="">
                    <a class="text-dark text-decoration-none" href="{{ route('user.editData') }}">Modificar datos</a>
                    <a class="text-dark text-decoration-none" href="{{ route('user.editPassword') }}">Modificar
                        contraseña</a>
                </div>
                <div class="">
                    <form method='POST' action='/user/updatePassword'>
                        @csrf
                        <div class='mt-5 d-flex'>
                            <label for='password' required>Contraseña</label>
                            <input type='password' name='password' id='password' placeholder='Contraseña'value=''>
                        </div>
                        <div class='mt-5 d-flex'>
                            <label for='password_confirmation' required>Repite contraseña</label>
                            <input type='password' name='password_confirmation' id='password_confirmation'
                                placeholder='Repite contraseña'value=''>
                        </div>
                        <div class="mt-5">
                            @if ($errors->has('password_confirmation'))
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>
                        <div class=''>
                            <button class="mt-5" type='submit'>Modificar contraseña</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{-- <a href="{{ route('home.index') }}" class="btn btn-info">Editar</a> --}}
            <a href="{{ route('home.index') }}" class="btn btn-default">Volver</a>
        </div>
    </div>
@endsection
