@extends('layouts.app-master')

@section('content')
<section class="usersShow">
    <div class="innerUsersShow">
        <div class="top">
            <h1>Empresa</h1>
            <h3 class="">Datos de la empresa seleccionada</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <table class="">
                    <thead>
                        <tr>
                            <th scope="col" width="15%">Empresa</th>
                            <th scope="col" width="15%">Email</th>
                            <th scope="col" width="10%">Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->username }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a href="{{ route('users.index') }}" class="buttonTextWt">Volver</a></button>
            <button class="stylingButtons green"><a href="{{ route('users.edit', $user->id) }}" class="buttonTextWt">Editar</a></button>
        </div>
    </div>
</section>
@endsection