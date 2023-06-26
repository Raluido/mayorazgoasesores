@extends('layouts.app-master')

@section('content')
<div class="employeesEdit">
    <div class="innerEmployeesEdit">
        <div class="top">
            <h1>Modificar empleado</h1>
            <h3 class="">Aqui puedes modificar los datos relativos a cada empleado</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form method="post" action="{{ route('employees.update', $employee->id) }}">
                    @method('patch')
                    @csrf

                    <div class="inputForm">
                        <select name="employeeIdSlc" id="" class="">
                            <option value="nif" class="">NIF</option>
                            <option value="nie" class="">NIE</option>
                            <option value="cif" class="">CIF</option>
                        </select>
                    </div>
                    <div class="inputForm">
                        <label for="employeeId" class="">ID empleado</label>
                        <input type="text" class="" name="employeeId" required>
                        @if ($errors->has('employeeId'))
                        <span class="">{{ $errors->first('employeeId') }}</span>
                        @endif
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('employees.index') }}" class="buttonTextWt">Volver</a></button>
                        <button type="submit" class="stylingButtons green buttonTextWt">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection