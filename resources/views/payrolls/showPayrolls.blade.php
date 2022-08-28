@extends('layouts.app-master')

@section('content')
    <div class="d-flex justify-content-center mt-5">
        <div class="bg-light p-5 rounded border">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" width="15%">Nif</th>
                        <th scope="col" width="10%">Dni</th>
                        <th scope="col" width="10%">Mes</th>
                        <th scope="col" width="10%">Año</th>
                        <th scope="col" width="1%" colspan="3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payrolls as $index)
                        <tr>
                            <td>{{ $index->nif }}</td>
                            <td>{{ $index->dni }}</td>
                            <td>{{ $index->month }}</td>
                            <td>{{ $index->year }}</td>
                            <td>
                                {!! Form::open([
                                    'method' => 'DELETE',
                                    'route' => ['payrolls.deletePayrolls', $index->id, $index->month, $index->year],
                                    'style' => 'display:inline',
                                ]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class=""><button class="btn btn-danger"><a class="text-decoration-none text-white"
                        href="{{ url('payrolls/deleteAll/' . $month . '/' . $year) }}">Eliminar</a></button>
            </div>
        </div>
    </div>
@endsection
