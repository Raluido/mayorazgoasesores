@extends('layouts.app-master')

@section('content')
    <div class="d-flex justify-content-center mt-5">
        <div class="bg-light p-5 rounded border">
            <form method="POST" name="showOthersDocumentsForm" action="/othersdocuments/show" enctype="multipart/form-data">
                <div class="form-group my-4">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <label for="month">Selecciona un mes</label>
                    <select name="month" id="month">
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
                <div class="form-group my-4" id="year">
                    <label for="year">Selecciona un año</label>
                    <select name="year" id="year">
                        <option value="22"></option>
                        <option value="22">2022</option>
                        <option value="23">2023</option>
                        <option value="24">2024</option>
                        <option value="25">2025</option>
                        <option value="26">2026</option>
                        <option value="27">2027</option>
                        <option value="28">2028</option>
                        <option value="29">2029</option>
                        <option value="30">2030</option>
                    </select>
                </div>
                <button type="submit" id="showCostsImputs" class="">
                    Acceder
                </button>
            </form>
        </div>
    </div>
@endsection
