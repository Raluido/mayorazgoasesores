@extends('layouts.app-master')

@section('content')
<div class="othersDocumentsDownload">
    <div class="innerOthersDocumentsDownload">
        <div class="top">
            <h1>Otros documentos del mes</h1>
            <h3 class="">Descargar otros documentos.</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form method="POST" name="monthyearForm" action="/othersdocuments/downloadList" enctype="multipart/form-data">
                    <div class="inputForm">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <label for="month">Selecciona un mes</label>
                        <select name="month" id="month">
                            <option value=""></option>
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
                    <div class="inputForm">
                        <label for="year">Selecciona un año</label>
                        <select name="year" id="year">
                            <option value=""></option>
                            @for($i = $presentYear; $i > ($presentYear-5); $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('home.index') }}" class="buttonTextWt">Volver</a></button>
                        <button type="submit" class="stylingButtons green buttonTextWt">Mostar los documentos</button>
                    </div>
                </form>
            </div>
        </div>
        @endsection