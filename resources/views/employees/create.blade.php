@extends('layouts.app-master')

@section('content')
<div class="employeesCreate">
    <div class="innerEmployeesCreate">
        <div class="top">
            <h1>Añadir empleado</h1>
            <h3 class="">Aqui puedes agregar nuevos empleados a la base de datos</h3>
        </div>
        <div class="bottom">
            <div class="">
                @include('layouts.partials.messages')
            </div>
            {{ var_dump(Session::get('errors')); }}
            <div class="innerBottom">
                <form method="POST" action="">
                    @csrf
                    <div class="inputForm">
                        <select name="companyIdSlc" id="" class="">
                            <option value="nif" class="">NIF</option>
                            <option value="nie" class="">NIE</option>
                            <option value="cif" class="">CIF</option>
                        </select>
                    </div>
                    <div class="inputForm">
                        <label for="companyId" class="">ID empresa</label>
                        <input value="{{ old('nif') }}" type="text" class="" name="companyId" required>
                        @if ($errors->has('companyId'))
                        <span class="">{{ $errors->first('companyId') }}</span>
                        @endif
                    </div>
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
                        <button type="submit" class="stylingButtons green buttonTextWt">Guardar empleado</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection