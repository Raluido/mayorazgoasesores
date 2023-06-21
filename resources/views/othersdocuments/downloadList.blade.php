@extends('layouts.app-master')

@section('content')
<section class="othersDocumentsList">
    <div class="innerOthersDocumentsList">
        <div class="top">
            <h1>Otros documentos del mes</h1>
            <h3 class="">Listar otros documentos.</h3>
        </div>
        <div class="bottom">
            <div class="">
                @include('layouts.partials.messages')
            </div>
            <div class="innerBottom">
                <form action="/othersdocuments/download" id="" method="post">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <table class="">
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
                                <td style="display:flex; align-items:center;"><input type="checkbox" name="othersDocuments[]" value="{{ $index->filename }}"><br />
                                <td><input type="hidden" name="month" value="{{ $index->month }}"><br />
                                <td><input type="hidden" name="year" value="{{ $index->year }}"><br />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($othersdocuments != null)
                    <div class="buttonsNav">
                        <button type="submit" class="stylingButtons green buttonTextWt">Descargar archivos seleccionados</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a href="{{ route('othersdocuments.downloadForm') }}" class="buttonTextWt">Volver</a></button>
        </div>
    </div>
</section>
@endsection