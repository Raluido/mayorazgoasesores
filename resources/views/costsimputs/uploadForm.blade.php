@extends('layouts.app-master')

@section('content')
<section class="costsImputsUpload">
    <div class="innerCostsImputsUpload">
        <div class="top">
            <h1>Imputación de costes del mes</h1>
            <h3 class="">Subir los modelos de inputación de costes.</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form action="/costsimputs/upload" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="">
                        <div class="inputForm">
                            <label for="month">Mes</label>
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
                        <div class="inputForm">
                            <label for="year">Año</label>
                            <select name="year" id="year">
                                @for($i = $presentYear; $i > ($presentYear-5); $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="inputForm">
                            <div class="">
                                <div class=""> <i class="fa fa-cloud-upload fa-3x"></i>
                                    <span class="">Adjunta el .pdf con los modelos de
                                        imputación de costes del
                                        mes</span>
                                </div>
                            </div>
                            <div class="">
                                <input type="file" class="" name="costsimputs" id="costsimputs" />
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div id="loaderIcon" class="spinner-border" style="display:none" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                        <div class="buttonsNav">
                            <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
                            <button type="submit" onclick="spinner()" class="stylingButtons green buttonTextWt">
                                Enviar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<div class="">
    @include('layouts.partials.messages')
</div>
@endsection
@section('js')
<script src="{{ asset('js/spinner.js') }}" defer></script>
@endsection