@extends('layouts.app-master')

@section('content')
    <div class="mt-5">
        <h1>Otros documentos</h1>
        <div class="lead">
            Gestión de otros documentos
        </div>
        <div class="w-50 mx-auto mt-4">
            <table class="table table-bordered">
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
                            <td>{{ $index->nif }}</td>
                            <td>{{ $index->filename }}</td>
                            <td>{{ $index->month }}</td>
                            <td>{{ $index->year }}</td>
                            <td>
                                {!! Form::open([
                                    'method' => 'DELETE',
                                    'route' => ['othersdocuments.deleteOthersDocuments', $index->id, $index->month, $index->year],
                                    'style' => 'display:inline',
                                ]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 d-flex justify-content-end"><button class="btn btn-danger"><a
                        class="text-decoration-none text-white"
                        href="{{ url('othersdocuments/downloadList/' . $month . '/' . $year) }}">Eliminar todos</a></button>
            </div>
        </div>
        <div class="d-flex mb-5">
            {!! $othersdocuments->links() !!}
        </div>
    </div>
@endsection
