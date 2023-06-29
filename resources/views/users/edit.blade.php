@extends('layouts.app-master')

@section('content')
<div class="editUsers">
    <div class="innerEditUsers">
        <div class="top">
            <h1>Modificar empresa</h1>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form method="post" action="{{ route('users.update', $user->id) }}">
                    @method('patch')
                    @csrf
                    <input type="hidden" value="{{ $user->id }}" name="id" class="">
                    <div class="inputDiv">
                        <label for="name" class="">Empresa</label>
                        <input value="{{ $user->name }}" type="text" class="" name="name" placeholder="Empresa" required>

                        @if ($errors->has('name'))
                        <span class="">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="inputDiv">
                        <label for="email" class="">Email</label>
                        <input value="{{ $user->email }}" type="email" class="" name="email" placeholder="Email address" required>
                        @if ($errors->has('email'))
                        <span class="">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('users.index') }}" class="buttonTextWt">Volver</button>
                        <button type="submit" class="stylingButtons green buttonTextWt">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection