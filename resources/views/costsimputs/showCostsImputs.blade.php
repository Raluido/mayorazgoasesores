@extends('layouts.app-master')

@section('content')
<section class="costsImputsShow">
    <div class="innerCostsImputsShow paddingFix">
        <div class="top">
            <h1>Imputación de costes</h1>
            <h3 class="">Gestión de imputación de costes</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <table class="">
                    <thead>
                        <tr>
                            <th scope="col" width="5%">Nif</th>
                            <th scope="col" width="5%">Mes</th>
                            <th scope="col" width="5%">Año</th>
                            <th scope="col" width="1%">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($costsimputs as $index)
                        <tr>
                            <td>{{ basename($index->nif) }}</td>
                            <td>{{ basename($index->month) }}</td>
                            <td>{{ basename($index->year) }}</td>
                            <td>
                                {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['costsimputs.deleteCostsImputs', $index->user_id, $index->year, $index->month],
                                'style' => 'display:inline',
                                ]) !!}
                                {!! Form::submit('Delete', ['class' => 'stylingButtons red buttonTextWt']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
            <button class="stylingButtons green"><a class="buttonTextWt" href="{{ url('costsimputs/deleteAll') }}">Eliminar todos</a></button>
        </div>
        <div class="d-flex mb-5">
            {!! $costsimputs->links() !!}
        </div>
    </div>
</section>
@endsection