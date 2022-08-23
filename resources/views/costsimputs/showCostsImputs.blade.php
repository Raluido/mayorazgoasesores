@extends('layouts.app-master')

@section('content')
    <div class="d-flex justify-content-center mt-5">
        <div class="bg-light p-5 rounded border">

            <div class=""><button class="btn btn-danger"><a class="text-decoration-none text-white"
                        href="{{ url('costsimputs/deleteAll/' . $month . '/' . $year) }}">Eliminar todos</a></button>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" width="15%">Nif</th>
                        <th scope="col" width="10%">Mes</th>
                        <th scope="col" width="10%">Año</th>
                        <th scope="col" width="1%" colspan="3"></th>
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

        </div>
    </div>
@endsection
