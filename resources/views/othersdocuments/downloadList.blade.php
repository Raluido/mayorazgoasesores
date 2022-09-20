@extends('layouts.app-master')

@section('content')
<div class="container">
    <div class="mt-3">
        <h4>Descarga de documentos</h4>
    </div>
    <div class="d-flex justify-content-center mt-5">
        <div class="bg-light p-5 rounded border">
            <form action="/othersdocuments/download" id="" method="post" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" width="5%">Nif</th>
                            <th scope="col" width="30%">Nombre</th>
                            <th scope="col" width="5%">Mes</th>
                            <th scope="col" width="5%">Año</th>
                            <th scope="col" width="3%">Seleccionar</th>
                            <th scope="col" width="1%" colspan="3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($othersdocuments as $index)
                        <tr>
                            <td>{{ $index->nif }}</td>
                            <td>{{ basename($index->filename) }}</td>
                            <td>{{ $index->month }}</td>
                            <td>{{ $index->year }}</td>
                            <td><input type="checkbox" name="othersDocuments[]" value="{{ $index->filename }}"><br />
                            <td><input type="hidden" name="month" value="{{ $index->month }}"><br />
                            <td><input type="hidden" name="year" value="{{ $index->year }}"><br />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($othersdocuments != null)
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit">Descargar archivos seleccionados</button>
                </div>
                @endif
            </form>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-5">
        <button class="btn btn-secondary"><a href="{{ route('home.index') }}" class="text-decoration-none text-white">Volver</a></button>
    </div>
</div>
@endsection