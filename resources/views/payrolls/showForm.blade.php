@extends('layouts.app-master')

@section('content')
<div class="payrollsForm">
    <div class="innerPayrollsForm">
        <div class="top">
            <h1 class="">Mostrar Nóminas</h1>
            <h3 class="">Selecciona las nóminas que quieras ver</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form method="get" name="showPayrollsForm" action="/payrolls/show" enctype="multipart/form-data">
                    <div class="inputForm">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
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
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
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
</div>
@endsection