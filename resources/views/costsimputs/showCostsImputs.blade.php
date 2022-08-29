@extends('layouts.app-master')

@section('content')
    <div class="mt-5">
        <h1>Imputación de costes</h1>
        <div class="lead">
            Gestión de imputación de costes
        </div>
        <div class="w-50 mx-auto mt-4">
            <table class="table table-striped">
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
                            <td>{{ $index->nif }}</td>
                            <td>{{ $index->month }}</td>
                            <td>{{ $index->year }}</td>
                            <td>
                                {!! Form::open([
                                    'method' => 'DELETE',
                                    'route' => ['costsimputs.deleteCostsImputs', $index->id, $index->month, $index->year],
                                    'style' => 'display:inline',
                                ]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-4 mb-4"><button class="btn btn-danger"><a
                        class="text-decoration-none text-white"
                        href="{{ url('costsimputs/deleteAll/' . $month . '/' . $year) }}">Eliminar todos</a></button>
            </div>
        </div>
        <div class="d-flex mb-5">
            {!! $costsimputs->links() !!}
        </div>
    </div>
@endsection
