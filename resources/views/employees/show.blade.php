@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Empleado</h1>
        <div class="lead">

        </div>

        <div class="container mt-4">
                <div>
                    Empresa: {{ $employeeData[0]->name }}
                </div>
                <div>
                    Nif: {{ $employeeData[0]->nif }}
                </div>
                <div>
                    Dni: {{ $employeeData[0]->dni }}
                </div>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('employees.edit', $employeeData[0]->id) }}" class="btn btn-info">Editar</a>
        <a href="{{ route('employees.index') }}" class="btn btn-default">Volver</a>
    </div>
@endsection
