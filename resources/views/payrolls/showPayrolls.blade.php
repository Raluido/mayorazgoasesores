@extends('layouts.app-master')

@section('content')
<section class="payrollsShow">
    <div class="innerPayrollsShow paddingFix">
        <div class="top">
            <h1>Nóminas</h1>
            <h3 class="">Gestión de nóminas</h3>
        </div>
        <div class="bottom">
            <table class="">
                <thead>
                    <tr>
                        <th scope="col" width="1%">#</th>
                        <th scope="col" width="30%">Nif</th>
                        <th scope="col" width="30%">Dni</th>
                        <th scope="col" width="10%">Mes</th>
                        <th scope="col" width="10%">Año</th>
                        <th scope="col" width="5%" colspan="3">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payrolls as $index)
                    <tr>
                        <th scope="row">{{ $index->id }}</th>
                        <td>{{ basename($index->nif) }}</td>
                        <td>{{ basename($index->dni) }}</td>
                        <td>{{ basename($index->month) }}</td>
                        <td>{{ basename($index->year) }}</td>
                        <td>
                            {{ html()->form('DELETE', '/payrolls/delete/' . $index->id)->open() }}
                            {{ html()->submit('Borrar')->class(['stylingButtons', 'red', 'buttonTextWt']) }}
                            {{ html()->form()->close() }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
            <button class="stylingButtons red"><a class="buttonTextWt" href="{{ url('payrolls/deleteAll') }}">Eliminar todas</a></button>
        </div>
        <div class="">
            {!! $payrolls->links() !!}
        </div>
    </div>
</section>
@endsection