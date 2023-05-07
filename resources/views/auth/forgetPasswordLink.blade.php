@extends('layouts.auth-master')

@section('content')
<section class="resetPassword">
    <div class="">
        <form action="{{ route('reset.password.post') }}" method="POST">
            <h1 class="card-header">Resetear Contraseña</h1>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="inputDiv">
                <div class="">
                    <label for="email_address" class="col-md-4 col-form-label text-md-right">Email</label>
                </div>
                <div class="col-md-6">
                    <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                    @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
            </div>

            <div class="inputDiv">
                <div class="">
                    <label for="password" class="col-md-4 col-form-label text-md-right">Contraseña</label>
                </div>
                <div class="col-md-6">
                    <input type="password" id="password" class="form-control" name="password" required autofocus>
                    @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>
            </div>

            <div class="inputDiv">
                <div class="">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirmar contraseña</label>
                </div>
                <div class="col-md-6">
                    <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required autofocus>
                    @if ($errors->has('password_confirmation'))
                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>
            </div>

            <div class="inputSubmit">
                <button type="submit" class="blue buttonTextWt">
                    Resetar Contraseña
                </button>
            </div>
        </form>
    </div>
</section>
@endsection