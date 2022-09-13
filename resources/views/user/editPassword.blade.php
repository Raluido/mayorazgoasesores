@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <div class="">
            <h1>Usuario</h1>
        </div>
        <div class="container mt-4">
            <div class="d-flex">
                <div class="mt-5 px-4 border">
                    <div class="mt-3"><a class="text-dark text-decoration-none" href="{{ route('user.editData') }}">Modificar
                            datos</a>
                    </div>
                    <div class="mt-3"><a class="text-dark text-decoration-none"
                            href="{{ route('user.editPassword') }}">Modificar
                            contraseña</a>
                    </div>
                </div>
                <div class="">
                    <div class="d-flex justify-content-center">
                        <form method='POST' action="{{ route('user.updatePassword') }}">
                            @csrf
                            <div class='mt-5 d-flex'>
                                <div class="row">
                                    <div class="col-5">
                                        <label for='password' required>Contraseña</label>
                                    </div>
                                    <div class="col-7">
                                        <input type='password' name='password' id='password' placeholder='Contraseña'
                                            value=''>
                                        @if ($errors->has('password'))
                                            <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class='mt-5 d-flex'>
                                <div class="row">
                                    <div class="col-5">
                                        <label for='password_confirmation' required>Repite contraseña</label>
                                    </div>
                                    <div class="col-7">
                                        <input type='password' name='password_confirmation' id='password_confirmation'
                                            placeholder='Repite contraseña'value=''>
                                        @if ($errors->has('password_confirmation'))
                                            <span
                                                class="text-danger text-left">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class=''>
                                <button class="mt-5" type='submit'>Modificar contraseña</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('home.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    @endsection
