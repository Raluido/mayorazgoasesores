@extends('layouts.app-master')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="w-50">
            <div class="px-5 py-5 mt-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-5 mt-3">
                        <div class="">
                            <h4>Añadir empresas automáticamente</h4>
                        </div>
                    </div>
                    <form action="/users/createAuto" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="my-3">
                            <div
                                class="relative h-40 rounded-lg border-dashed border-2 border-gray-200 bg-white flex justify-center items-center hover:cursor-pointer">
                                <div class="absolute">
                                    <div class="flex flex-col items-center mb-3"> <i
                                            class="fa fa-cloud-upload fa-3x text-gray-200"></i>
                                        <span class="block text-gray-400 font-normal">Adjunta el .pdf con las nóminas del
                                            mes</span>
                                    </div>
                                </div>
                                <div class="">
                                    <input type="file" class="" name="payrolls" id="payrolls" />
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
    <div class="d-flex justify-content-center mt-5">
        <button class="btn btn-secondary"><a href="{{ route('home.index') }}"
                class="text-decoration-none text-white">Volver</a></button>
    </div>
    @if (!empty($successMsg))
        <div class="alert alert-success mt-3"> {{ $successMsg }}</div>
    @endif
@endsection
@section('js')
    <script src="{{ asset('js/spinner.js') }}" defer></script>
@endsection
