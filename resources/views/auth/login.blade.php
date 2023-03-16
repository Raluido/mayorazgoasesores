@extends('layouts.auth-master')

@section('content')
<section class="login">
    <form method="post" action="{{ route('login.perform') }}">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <h1 class="">Login</h1>

        <div class="">
            <div class="">

                @include('layouts.partials.messages')

                <div class="inputDiv">
                    <input type="text" class="" name="nif" value="{{ old('nif') }}" placeholder="Nif" required="required" autofocus>
                    <label for="floatingName">Email o Nif</label>
                    @if ($errors->has('nif'))
                    <span class="">{{ $errors->first('nif') }}</span>
                    @endif
                </div>

                <div class="inputDiv">
                    <input type="password" class="" name="password" value="{{ old('password') }}" placeholder="Password" required="required">
                    <label for="floatingPassword">Contraseña</label>
                    @if ($errors->has('password'))
                    <span class="">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="inputDiv">
                    <button class="" type="submit">Login</button>
                    <div class="forgetPass">
                        <label class="">
                            <a class="" href="{{ route('forget.password.get') }}">Olvidé la
                                contraseña</a>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- @include('auth.partials.copy') -->
    </form>
</section>
@endsection