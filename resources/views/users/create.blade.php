@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Añadir nueva empresa</h1>
        <div class="lead">
            No olvide seleccionar el rol que éste usuario tendrá en la web
        </div>

        <div class="container mt-4 w-50">
            <form method="POST" action="">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input value="{{ old('name') }}" type="text" class="form-control" name="name" placeholder="Nombre"
                        required>

                    @if ($errors->has('name'))
                        <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input value="{{ old('email') }}" type="email" class="form-control" name="email"
                        placeholder="Email" required>
                    @if ($errors->has('email'))
                        <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="nif" class="form-label">Nif</label>
                    <input value="{{ old('nif') }}" type="text" class="form-control" name="nif" placeholder="Nif"
                        required>
                    @if ($errors->has('nif'))
                        <span class="text-danger text-left">{{ $errors->first('nif') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Rol</label>
                    <select class="form-control" name="role" class="" required>
                        <option value="">Seleccionar rol</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ in_array($role->name, $userRole) ? 'selected' : '' }}>
                                {{ $role->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('role'))
                        <span class="text-danger text-left">{{ $errors->first('role') }}</span>
                    @endif
                </div>
                <div class="d-flex justify-content-around mt-4">
                    <div class=""><button type="submit" class="btn btn-primary">Guardar usuario</button></div>
                    <div class=""><a href="{{ route('users.index') }}" class="btn btn-secondary">Volver</a></div>
                </div>
            </form>
        </div>

    </div>
@endsection
