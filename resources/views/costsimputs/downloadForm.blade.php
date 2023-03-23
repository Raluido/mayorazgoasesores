@extends('layouts.app-master')

@section('content')
<div class="d-flex justify-content-center mt-5">
    <div class="bg-light p-5 rounded border">
        <form method="POST" name="monthyearForm" action="/costsimputs/download" enctype="multipart/form-data">
            <div class="form-group my-4">
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
            <div class="form-group my-4 d-none" id="hiddeYear">
                <label for="year">Selecciona un año</label>
                <select name="year" id="year" onChange="Visibility1()">
                    <option value=""></option>
                    @for($i = $presentYear; $i > ($presentYear-5); $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" id="form_execute" class="d-none">
                Seleccionar fecha
            </button>
        </form>
        <div class="mt-5">
            @if ($month && $year != null)
            <a href="{{ url('/costsimputs/download/' . $month . '/' . $year) }}" class="btn btn-info">Descargar
                imputación de costes de
                {{ ' ' . $month . ' ' . $year }}</a>
            @else
            <p>Debes seleccionar un mes y un año.</p>
            @endif
        </div>
    </div>
</div>
</div>
<div class="d-flex justify-content-center mt-5">
    <button class="btn btn-secondary"><a href="{{ route('home.index') }}" class="text-decoration-none text-white">Volver</a></button>
</div>
@endsection
@section('js')
<script src="{{ asset('js/monthyear.js') }}" defer></script>
@endsection