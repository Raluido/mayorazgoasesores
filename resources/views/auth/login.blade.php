@extends('layouts.auth-master')

@section('content')
<main class="form-signin">
    <section class="login">
        <form method="post" action="{{ route('login.perform') }}">

            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

            <h1 class="">Login</h1>

            <div class="">
                <div class="">

                    <div class="inputDiv">
                        <input type="text" class="" name="nif" value="{{ old('nif') }}" placeholder="Email o nif" required="required" autofocus>
                        <label for="floatingName">Email o Nif</label>
                        @if ($errors->has('nif'))
                        <span class="red" style="display:block;">{{ $errors->first('nif') }}</span>
                        @endif
                    </div>

                    <div class="inputDiv">
                        <input type="password" class="" name="password" value="{{ old('password') }}" placeholder="Password" required="required">
                        <label for="floatingPassword">Contraseña</label>
                        @if ($errors->has('password'))
                        <span class="red" style="display:block;">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="forgetPass">
                        <label class="">
                            <a class="" href="{{ route('forget.password.get') }}">Olvidé la
                                contraseña</a>
                        </label>
                    </div>
                    <div class="inputSubmit">
                        <button class="blue"><a href="{{ route('home.index') }}" class="buttonTextWt">Volver</a></button>
                        <button class="green buttonTextWt" type="submit">Acceder</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</main>
@endsection