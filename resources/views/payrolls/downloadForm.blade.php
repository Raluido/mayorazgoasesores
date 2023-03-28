@extends('layouts.app-master')

@section('content')
<section class="payrollsDownload">
    <div class="innerPayrollsDownload">
        <div class="top">
            <h1>Nóminas del mes</h1>
            <h3 class="">Descargar nóminas.</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form method="POST" name="monthyearForm" action="/payrolls/download" enctype="multipart/form-data">
                    <div class="inputForm">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <label for="month">Selecciona un mes</label>
                        <select name="month" id="month" onChange="Visibility0()">
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
                    <div class="inputForm" id="hiddeYear">
                        <label for="year">Selecciona un año</label>
                        <select name="year" id="year" onChange="Visibility1()">
                            <option value=""></option>
                            @for($i = $presentYear; $i > ($presentYear-5); $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" id="form_execute" class="d-none">Seleccionar fecha</button>
                </form>
                <div class="downloadButton">
                    @if ($month && $year != null)
                    <a href="{{ url('/payrolls/download/' . $month . '/' . $year) }}" class="stylingButtons green buttonTextWt">Descargar nóminas
                        de
                        {{ ' ' . $month . ' ' . $year }}</a>
                    @else
                    <p>Debes seleccionar un mes y un año.</p>
                    @endif
                </div>
                <div class="buttonsNav">
                    <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script src="{{ asset('js/monthyear.js') }}" defer></script>
@endsection