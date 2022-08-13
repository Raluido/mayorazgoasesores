@extends('layouts.auth-master')

@section('content')
    <form method="post" action="{{ route('login.perform') }}">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        {{-- <img class="mb-4" src="{!! url('images/bootstrap-logo.svg') !!}" alt="" width="72" height="57"> --}}

        <h1 class="h3 mb-3 fw-normal mt-5">Login</h1>

        <div class="d-flex justify-content-center">
            <div class="w-25 mt-5">

                @include('layouts.partials.messages')

                <div class="form-group form-floating mb-3">
                    <input type="text" class="form-control" name="nif" value="{{ old('nif') }}"
                        placeholder="Nif" required="required" autofocus>
                    <label for="floatingName">Email o Nif</label>
                    @if ($errors->has('nif'))
                        <span class="text-danger text-left">{{ $errors->first('nif') }}</span>
                    @endif
                </div>

                <div class="form-group form-floating mb-3">
                    <input type="password" class="form-control" name="password" value="{{ old('password') }}"
                        placeholder="Password" required="required">
                    <label for="floatingPassword">Password</label>
                    @if ($errors->has('password'))
                        <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
            </div>
        </div>
        @include('auth.partials.copy')
    </form>
@endsection
