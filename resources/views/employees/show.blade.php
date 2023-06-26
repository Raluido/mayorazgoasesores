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
                <table class="">
                    <thead>
                        <tr>
                            <th scope="col" width="15%">Empresa</th>
                            <th scope="col" width="15%">Nif</th>
                            <th scope="col" width="10%">Dni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $employee[0]->name }}</td>
                            <td>{{ $employee[0]->nif }}</td>
                            <td>{{ $employee[0]->dni }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons green"><a href="{{ route('employees.index') }}" class="buttonTextWt">Volver</a></button>
            <button class="stylingButtons blue"><a href="{{ route('employees.edit', $employee[0]->id) }}" class="buttonTextWt">Editar</a></button>
        </div>
    </div>
    @endsection