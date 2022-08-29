@extends('layouts.app-master')

@section('content')
    <div class="d-flex justify-content-center mt-5">
        <div class="bg-light p-5 rounded border">
            <form action="/othersdocuments/download" id="" method="post" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" width="15%">Nif</th>
                            <th scope="col" width="15%">Nombre</th>
                            <th scope="col" width="10%">Mes</th>
                            <th scope="col" width="10%">Año</th>
                            <th scope="col" width="3%">Seleccionar</th>
                            <th scope="col" width="1%" colspan="3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($othersdocuments as $index)
                            <tr>
                                <td>{{ $index->nif }}</td>
                                <td>{{ $index->filename }}</td>
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
                <button type="submit">Descargar archivos seleccionados</button>
            </form>
        </div>
    </div>
@endsection
