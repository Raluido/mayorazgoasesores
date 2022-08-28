@extends('layouts.app-master')

@section('content')
    <div class="d-flex justify-content-center mt-5">
        <div class="bg-light p-5 rounded border">
            <form method="POST" name="monthyearForm" action="/costsimputs/download" enctype="multipart/form-data">
                <div class="form-group my-4">
                    <label for="nif">Escribe un Nif</label>
                    <input name="nif" id="nif" value="">
                </div>
                <div class="form-group my-4">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <label for="month">Selecciona un mes</label>
                    <select name="month" id="month" onChange="Visibility0()">
                        <option value="ENE"></option>
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
                        <option value="2022"></option>
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
                <button type="submit" id="form_execute" class="d-none">
                    Descargar la empresa seleccionada
                </button>
            </form>
            <div class="mt-5">
                @if ($month || $year != null)
                    <a href="{{ url('/costsimputs/download/' . $month . '/' . $year . '/' . $nif) }}"
                        class="btn btn-info">Descargar
                        modelo imputación de costes
                        de laempresa seleccionada
                        {{ ' ' . $month . ' ' . $year }}</a>
                    <a href="{{ url('/costsimputs/download/' . $month . '/' . $year) }}" class="btn btn-info">Descargar
                        modelo imputación de costes
                        de todas las empresas
                        {{ ' ' . $month . ' ' . $year }}</a>
                @else
                    <p>Debes seleccionar un mes y un año.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/monthyear.js') }}" defer></script>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
