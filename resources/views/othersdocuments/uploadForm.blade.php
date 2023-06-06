@extends('layouts.app-master')

@section('content')
<section class="othersDocumentsUpload">
    <div class="innerOthersDocumentsUpload">
        <div class="top">
            <h1>Otros documentos</h1>
            <h3 class="">Subir otros documentos para cada empresa individualmente.</h3>
        </div>
        <div class="bottom">
            <div class="">
                @include('layouts.partials.messages')
            </div>
            <div class="innerBottom">
                <form action="/othersdocuments/upload" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="inputForm">
                        <label for="nif">Nif</label>
                        <input type="text" class="" name="nif" id="nif" />
                    </div>
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
                            <input type="file" class="" name="othersdocuments[]" id="othersdocuments" multiple />
                        </div>
                    </div>
                    <div class="buttonsNav">
                        <div id="loaderIcon" class="spinner-border" style="display:none" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                        <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
                        <button type="submit" onclick="spinner()" class="stylingButtons green buttonTextWt">
                            Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script src="{{ asset('js/spinner.js') }}" defer></script>
@endsection