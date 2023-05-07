@extends('layouts.auth-master')

@section('content')
<section class="resetPassword">
    <div class="">
        @if (Session::has('message'))
        <div class="" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif
        <form action="{{ route('forget.password.post') }}" method="POST">
            <h1 class="">Resetear contraseña</h1>
            @csrf
            <div class="">
                <div class="">
                    <div class="inputDiv">
                        <input type="email" id="email" class="" name="email" required autofocus>
                        <label for="email" class="">Email</label>
                        @if ($errors->has('email'))
                        <span class="">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="inputSubmit">
                        <button type="submit" class="blue buttonTextWt">
                            Enviar link para resetear la contraseña
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection