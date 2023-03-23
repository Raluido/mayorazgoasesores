@extends('layouts.app-master')

@section('content')
<section class="costsImputsForm">
    <div class="innerCostsImputsForm">
        <div class="top">
            <h1 class="">Imputación de Costes del Mes</h1>
            <h3 class="">Subir los modelos de Imputación de Costes del mes seleccionado.</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form method="get" name="showCostsImputsForm" action="/costsimputs/show" enctype="multipart/form-data">
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
                            @for($i = $presentYear; $i > ($presentYear-5); $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
                        <button type="submit" id="showCostsImputs" class="stylingButtons green buttonTextWt">
                            Acceder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    @endsection