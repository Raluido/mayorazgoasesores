@extends('layouts.app-master')

@section('content')
<div class="users">
    <div class="innerUsers">
        <div class="top">
            <h1>Empresas</h1>
        </div>
        <div class="bottom">
            <div class="addDelButtons">
                <button class="stylingButtons green"><a href="{{ route('users.create') }}" class="buttonTextWt">Añadir nueva empresa</a></button>
                <a class="" id="confirmationBtn" href="{{ url('users/deleteAll') }}"></a>
                <button class="stylingButtons red buttonTextWt" onclick="confirmation()">Eliminar
                    todas</button>
            </div>
            <div class="">
                @include('layouts.partials.messages')
            </div>

            <table class="">
                <thead>
                    <tr>
                        <th scope="col" width="1%">#</th>
                        <th scope="col" width="15%">Empresa</th>
                        <th scope="col" width="15%">Nif</th>
                        <th scope="col" width="15%">Email</th>
                        <th scope="col" width="10%">Rol</th>
                        <th scope="col" width="1%" colspan="3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->nif }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach ($user->roles as $role)
                            <span class="">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td><button class="stylingButtons blue"><a href="{{ route('users.show', $user->id) }}" class="buttonTextWt">Mostrar</a></button></td>
                        <td><button class="stylingButtons green"><a href="{{ route('users.edit', $user->id) }}" class="buttonTextWt">Editar</a></td></button>
                        <td>
                            {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'style' => 'display:inline']) !!}
                            {!! Form::submit('Eliminar', ['class' => 'stylingButtons red buttonTextWt']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="buttonsNav">
                <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
            </div>
            <div class="d-flex mb-5">
                {!! $users->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('js/confirmation.js') }}" defer></script>
@endsection