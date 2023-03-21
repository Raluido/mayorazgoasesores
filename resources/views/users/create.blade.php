@extends('layouts.app-master')

@section('content')
<section class="createUsers">
    <div class="innerCreateUsers">
        <div class="top">
            <h1>Añadir nueva empresa</h1>
            <h3 class="">No olvide seleccionar el rol que éste usuario tendrá en la web.</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form method="POST" action="">
                    @csrf
                    <div class="inputDiv">
                        <label for="name" class="form-label">Nombre</label>
                        <input value="{{ old('name') }}" type="text" class="form-control" name="name" placeholder="Nombre" required>

                        @if ($errors->has('name'))
                        <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="inputDiv">
                        <label for="email" class="form-label">Email</label>
                        <input value="{{ old('email') }}" type="email" class="form-control" name="email" placeholder="Email" required>
                        @if ($errors->has('email'))
                        <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="inputDiv">
                        <label for="nif" class="form-label">Nif</label>
                        <input value="{{ old('nif') }}" type="text" class="form-control" name="nif" placeholder="Nif" required>
                        @if ($errors->has('nif'))
                        <span class="text-danger text-left">{{ $errors->first('nif') }}</span>
                        @endif
                    </div>
                    <div class="inputDiv">
                        <label for="role" class="form-label">Rol</label>
                        <select class="form-control" name="role" class="" required>
                            <option value="">Seleccionar rol</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ in_array($role->name, $userRole) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                            @endforeach
                        </select>
                        @if ($errors->has('role'))
                        <span class="text-danger text-left">{{ $errors->first('role') }}</span>
                        @endif
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('users.index') }}" class="buttonTextWt">Volver</a></button>
                        <button type="submit" class="stylingButtons green buttonTextWt">Guardar usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection