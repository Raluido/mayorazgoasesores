@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Empleado</h1>
        <div class="lead">

        </div>

        <div class="container mt-4">
            <div>
                Empresa: {{ $employee->name }}
            </div>
            <div>
                Nif: {{ $employee->nif }}
            </div>
            <div>
                Dni: {{ $employee->dni }}
            </div>
        </div>

    </div>
    <div class="mt-4">
        <a href="{{ route('employees.edit', $user->id) }}" class="btn btn-info">Editar</a>
        <a href="{{ route('employees.index') }}" class="btn btn-default">Volver</a>
    </div>
@endsection
