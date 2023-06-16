@extends('layouts.app-master')

@section('content')
<div class="employeesCreate">
    <div class="innerEmployeesCreate">
        <div class="top">
            <h1>Añadir empleado</h1>
            <h3 class="">Aqui puedes agregar nuevos empleados a la base de datos</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form method="POST" action="">
                    @csrf

                    <div class="inputForm">
                        <select name="companyId" id="" class="">
                            <option value="nif" class="">NIF</option>
                            <option value="nie" class="">NIE</option>
                            <option value="cif" class="">CIF</option>
                        </select>
                    </div>
                    <div class="inputForm">
                        <label for="nif" class="">ID empresa</label>
                        <input value="{{ old('nif') }}" type="text" class="" name="nif" placeholder="" required>
                        @if ($errors->has('nif'))
                        <span class="">{{ $errors->first('nif') }}</span>
                        @endif
                    </div>
                    <div class="inputForm">
                        <select name="employeeId" id="" class="">
                            <option value="nif" class="">NIF</option>
                            <option value="nie" class="">NIE</option>
                            <option value="cif" class="">CIF</option>
                        </select>
                    </div>
                    <div class="inputForm">
                        <label for="dni" class="">ID empleado</label>
                        <input type="text" class="" name="dni" placeholder="" required>
                        @if ($errors->has('dni'))
                        <span class="">{{ $errors->first('dni') }}</span>
                        @endif
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('employees.index') }}" class="buttonTextWt">Volver</a></button>
                        <button type="submit" class="stylingButtons green buttonTextWt">Guardar empleado</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection