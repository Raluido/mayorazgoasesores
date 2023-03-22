@extends('layouts.app-master')

@section('content')
<div class="employeesShow">
    <div class="innerEmployeesShow">
        <div class="top">
            <h1>Empleado</h1>
            <h3 class="">Los datos del trabajador seleccionado.</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
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
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a href="{{ route('employees.edit', $employeeData[0]->id) }}" class="buttonTextWt">Editar</a></button>
            <button class="stylingButtons green"><a href="{{ route('employees.index') }}" class="buttonTextWt">Volver</a></button>
        </div>
    </div>
    @endsection