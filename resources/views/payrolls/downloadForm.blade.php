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
                    <button type="submit" id="form_execute" class="stylingButtons green buttonTextWt">Seleccionar fecha</button>
                </form>
                <div class="">
                    @if ($month && $year != null)
                    <a href="{{ url('/payrolls/download/' . $month . '/' . $year) }}" class="btn btn-info">Descargar nóminas
                        de
                        {{ ' ' . $month . ' ' . $year }}</a>
                    @else
                    <p>Debes seleccionar un mes y un año.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="">
            <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
        </div>
    </div>
</section>
@endsection
@section('js')
<script src="{{ asset('js/monthyear.js') }}" defer></script>
@endsection