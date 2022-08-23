@extends('layouts.app-master')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="w-50">
            <div class="px-5 py-5 mt-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-5 mt-3">
                        <div class="">
                            <h4>Imputación de costes del mes</h4>
                        </div>
                    </div>
                    <form action="/costsimputs/upload" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="my-3">
                            <div class="form-group my-4">
                                <label for="month">Mes</label>
                                <select name="month" id="month">
                                    <option value="Enero">Enero</option>
                                    <option value="Febrero">Febrero</option>
                                    <option value="Marzo">Marzo</option>
                                    <option value="Abril">Abril</option>
                                    <option value="Mayo">Mayo</option>
                                    <option value="Junio">Junio</option>
                                    <option value="Julio">Julio</option>
                                    <option value="Agosto">Agosto</option>
                                    <option value="Septiembre">Septiembre</option>
                                    <option value="Octubre">Octubre</option>
                                    <option value="Noviembre">Noviembre</option>
                                    <option value="Diciembre">Diciembre</option>
                                </select>
                            </div>
                            <div class="form-group my-4">
                                <label for="year">Año</label>
                                <select name="year" id="year">
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
                            <div
                                class="relative h-40 rounded-lg border-dashed border-2 border-gray-200 bg-white flex justify-center items-center hover:cursor-pointer">
                                <div class="absolute">
                                    <div class="flex flex-col items-center mb-3"> <i
                                            class="fa fa-cloud-upload fa-3x text-gray-200"></i>
                                        <span class="block text-gray-400 font-normal">Adjunta el .pdf con los modelos de imputación de costes del
                                            mes</span>
                                    </div>
                                </div>
                                <div class="">
                                    <input type="file" class="" name="costsimputs" id="costsimputs" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 text-center pb-3">
                            <div id="loaderIcon" class="spinner-border text-primary" style="display:none" role="status">
                                <span class="sr-only">Cargando...</span>
                            </div>
                            <button type="submit" onclick="spinner()"
                                class="mt-4 w-full h-12 text-lg w-32 bg-blue-600 rounded text-dark hover:bg-blue-700 btn btn-outline-secondary btn-lg">
                                Enviar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if (!empty($successMsg))
        <div class="alert alert-success mt-3"> {{ $successMsg }}</div>
    @endif
@endsection
@section('js')
    <script src="{{ asset('js/spinner.js') }}" defer></script>
@endsection
