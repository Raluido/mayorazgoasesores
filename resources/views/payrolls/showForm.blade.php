@extends('layouts.app-master')

@section('content')
<section class="payrollsForm">
    <div class="innerPayrollsForm">
        <div class="top">
            <h1 class="">Mostrar Nóminas</h1>
            <h3 class="">Selecciona las nóminas que quieras ver</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form method="GET" name="showPayrollsForm" action="{{ route('payrolls.showPayrolls') }}">
                    <div class="inputForm">
                        <label for="month">Selecciona un mes</label>
                        <select name="month" id="month">
                            <option value="ENE">Enero</option>
                            <option value="FEB">Febrero</option>
                            <option value="MAR">Marzo</option>
                            <option value="ABR">Abril</option>
                            <option value="MAY">Mayo</option>
                            <option value="JUN">Junio</option>
                            <option value="JUL">Julio</option>
                            <option value="AGO">Agosto</option>
                            <option value="SEP">Septiembre</option>
                            <option value="OCT">Octubre</option>
                            <option value="NOV">Noviembre</option>
                            <option value="DIC">Diciembre</option>
                        </select>
                    </div>
                    <div class="inputForm" id="year">
                        <label for="year">Selecciona un año</label>
                        <select name="year" id="year">
                            @for($i = $presentYear; $i > ($presentYear-5); $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
                        <button type="submit" id="showPayrolls" class="stylingButtons green buttonTextWt">
                            Acceder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection