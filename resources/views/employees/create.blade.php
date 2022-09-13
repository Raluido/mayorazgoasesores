@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Añadir nuevo empleado</h1>

        <div class="container mt-5 w-50">
            <form method="POST" action="">
                @csrf
                <div class="mb-3">
                    <label for="nif" class="form-label">Nif empresa</label>
                    <input value="{{ old('nif') }}" type="text" class="form-control" name="nif" placeholder="Nif"
                        required>
                    @if ($errors->has('nif'))
                        <span class="text-danger text-left">{{ $errors->first('nif') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="dni" class="form-label">Dni empleado</label>
                    <input type="text" class="form-control" name="dni" placeholder="dni" required>
                    @if ($errors->has('dni'))
                        <span class="text-danger text-left">{{ $errors->first('dni') }}</span>
                    @endif
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Guardar empleado</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary ms-3">Volver</a>
                </div>
            </form>
        </div>
    </div>
@endsection
