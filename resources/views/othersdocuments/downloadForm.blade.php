@extends('layouts.app-master')

@section('content')
<section class="othersDocumentsDownload">
    <div class="innerOthersDocumentsDownload">
        <div class="top">
            <h1>Otros documentos del mes</h1>
            <h3 class="">Descargar otros documentos</h3>
        </div>
        <div class="bottom">
            <div class="">
                @include('layouts.partials.messages')
            </div>
            <div class="innerBottom">
                <form method="get" name="monthyearForm" action="/othersdocuments/downloadList">
                    <div class="inputForm">
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
                        <button type="submit" class="stylingButtons green buttonTextWt">Mostar documentos</button>
                        <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection