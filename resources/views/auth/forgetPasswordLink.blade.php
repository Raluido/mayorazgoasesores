@extends('layouts.app-master')

@section('content')
    <main class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mt-5">
                        <div class="card-header">Resetear Contraseña</div>
                        <div class="card-body">

                            <form action="{{ route('reset.password.post') }}" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group row">
                                    <div class="">
                                        <label for="email_address"
                                            class="col-md-4 col-form-label text-md-right">Email</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" id="email_address" class="form-control" name="email"
                                            required autofocus>
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="">
                                        <label for="password"
                                            class="col-md-4 col-form-label text-md-right">Contraseña</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="password" id="password" class="form-control" name="password" required
                                            autofocus>
                                        @if ($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <div class="">
                                        <label for="password-confirm"
                                            class="col-md-4 col-form-label text-md-right">Confirmar contraseña</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="password" id="password-confirm" class="form-control"
                                            name="password_confirmation" required autofocus>
                                        @if ($errors->has('password_confirmation'))
                                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4s">
                                    <button type="submit" class="btn btn-primary">
                                        Resetar Contraseña
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
