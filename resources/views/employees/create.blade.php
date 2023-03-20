@extends('layouts.app-master')

@section('content')
<div class="employeesCreate">
    <div class="innerEmployeesCreate">
        <div class="top">
            <h1>Añadir nuevo empleado</h1>
        </div>
        <div class="bottom">
            <form method="POST" action="">
                @csrf
                <div class="mb-3">
                    <label for="nif" class="">Nif empresa</label>
                    <input value="{{ old('nif') }}" type="text" class="" name="nif" placeholder="Nif" required>
                    @if ($errors->has('nif'))
                    <span class="">{{ $errors->first('nif') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="dni" class="">Dni empleado</label>
                    <input type="text" class="" name="dni" placeholder="dni" required>
                    @if ($errors->has('dni'))
                    <span class="">{{ $errors->first('dni') }}</span>
                    @endif
                </div>
            </form>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a href="{{ route('employees.index') }}" class="buttonTextWt">Volver</a></button>
            <button type="submit" class="stylingButtons green buttonTextWt">Guardar empleado</button>
        </div>
    </div>
</div>
@endsection