@extends('layouts.app-master')

@section('content')
<section class="othersDocumentsShow">
    <div class="innerOthersDocumentsShow paddingFix">
        <div class="top">
            <h1>Otros documentos</h1>
            <h3 class="">Puedes visualizar los documentos.</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <table class="">
                    <thead>
                        <tr>
                            <th scope="col" width="15%">Nif</th>
                            <th scope="col" width="15%">Nombre</th>
                            <th scope="col" width="10%">Mes</th>
                            <th scope="col" width="10%">Año</th>
                            <th scope="col" width="1%" colspan="3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($othersdocuments as $index)
                        <tr>
                            <td>{{ basename($index->nif) }}</td>
                            <td>{{ basename($index->filename) }}</td>
                            <td>{{ basename($index->month) }}</td>
                            <td>{{ basename($index->year) }}</td>
                            <td>
                                {{ html()->form('DELETE', '/othersdocuments/delete/' . $index->id . '/' . $index->month . '/' . $index->year)->open() }}
                                {{ html()->submit('Borrar')->class(['stylingButtons', 'red', 'buttonTextWt']) }}
                                {{ html()->form()->close() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
            <button class="stylingButtons red"><a class="buttonTextWt" href="{{ url('othersdocuments/deleteAll') }}">Eliminar todos</a></button>
        </div>
        <div class="">
            {!! $othersdocuments->links() !!}
        </div>
    </div>
</section>
@endsection