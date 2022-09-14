@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Modificar empleado</h1>
        <div class="lead">

        </div>

        <div class="container mt-5 w-25">
            <form method="post" action="{{ route('employees.update', $employeeFix[0]->id) }}">
                @method('patch')
                @csrf
                <div class="mb-3">
                    <label for="nif" class="form-label">Nif</label>
                    <input value="{{ $employeeFix[0]->nif }}" type="text" class="form-control" name="nif"
                        placeholder="Nif" required>

                    @if ($errors->has('nif'))
                        <span class="text-danger text-left">{{ $errors->first('nif') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="dni" class="form-label">Dni</label>
                    <input value="{{ $employeeFix[0]->dni }}" type="text" class="form-control" name="dni"
                        placeholder="Dni" required>
                    @if ($errors->has('dni'))
                        <span class="text-danger text-left">{{ $errors->first('dni') }}</span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('employees.index') }}" class="btn btn-default">Cancelar</button>
            </form>
        </div>

    </div>
@endsection
