@extends('layouts.app-master')

@section('content')
    <div class="mt-5">
        <h1>Nóminas</h1>
        <div class="lead">
            Gestión de nóminas
        </div>
        <div class="w-50 mx-auto mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
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
            <div class="d-flex justify-content-center mt-4 mb-4"><button class="btn btn-danger"><a
                        class="text-decoration-none text-white"
                        href="{{ url('payrolls/deleteAll/' . $month . '/' . $year) }}">Eliminar todas</a></button>
            </div>
        </div>
        <div class="d-flex mb-5">
            {!! $payrolls->links() !!}
        </div>
    </div>
@endsection
