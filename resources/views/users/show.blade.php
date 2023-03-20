@extends('layouts.app-master')

@section('content')
<div class="usersShow">
    <div class="innerUsersShow">
        <div class="top">
            <h1>Empresa</h1>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <div>
                    Empresa: {{ $user->name }}
                </div>
                <div>
                    Email: {{ $user->email }}
                </div>
                <div>
                    Nombre empresa: {{ $user->username }}
                </div>
            </div>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a href="{{ route('users.index') }}" class="buttonTextWt">Volver</a></button>
            <button class="stylingButtons green"><a href="{{ route('users.edit', $user->id) }}" class="buttonTextWt">Editar</a></button>
        </div>
    </div>
</div>
@endsection