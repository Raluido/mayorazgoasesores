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
                <form method="post" action="{{ route('employees.update', $employeeFix[0]->id) }}">
                    @method('patch')
                    @csrf
                    <div class="inputForm">
                        <label for="nif" class="form-label">Nif</label>
                        <input value="{{ $employeeFix[0]->nif }}" type="text" class="form-control" name="nif" placeholder="Nif" required>

                        @if ($errors->has('nif'))
                        <span class="text-danger text-left">{{ $errors->first('nif') }}</span>
                        @endif
                    </div>
                    <div class="inputForm">
                        <label for="dni" class="form-label">Dni</label>
                        <input value="{{ $employeeFix[0]->dni }}" type="text" class="form-control" name="dni" placeholder="Dni" required>
                        @if ($errors->has('dni'))
                        <span class="text-danger text-left">{{ $errors->first('dni') }}</span>
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