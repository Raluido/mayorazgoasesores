@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Empleados</h1>
        <div class="d-flex justify-content-end">
            <div class="">
                <button class="btn btn-primary me-5"><a href="{{ route('employees.create') }}"
                        class="text-decoration-none text-white">Añadir nuevo empleado</a>
            </div>
            <div class="">
                <button class="btn btn-danger" onclick="confirmation()">Eliminar
                    todos</button>
                <a class="d-none" id="confirmationBtn" href="{{ url('employees/deleteAll') }}"></a>
            </div>
        </div>

        <div class="mt-2">
            @include('layouts.partials.messages')
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col" width="1%">#</th>
                    <th scope="col" width="10%">Empresa</th>
                    <th scope="col" width="8%">Nif</th>
                    <th scope="col" width="8%">Dni</th>
                    <th scope="col" width="1%" colspan="3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $index)
                    <tr>
                        <th scope="row">{{ $index->id }}</th>
                        <td>{{ $index->name }}</td>
                        <td>{{ $index->nif }}</td>
                        <td>{{ $index->dni }}</td>
                        <td><a href="{{ route('employees.show', $index->id) }}"
                                class="btn btn-warning btn-sm">Mostrar</a>
                        </td>
                        <td><a href="{{ route('employees.edit', $index->id) }}" class="btn btn-info btn-sm">Editar</a>
                        </td>
                        <td>
                            {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['employees.destroy', $index->id],
                                'style' => 'display:inline',
                            ]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            <button class="btn btn-secondary"><a href="{{ route('home.index') }}"
                    class="text-decoration-none text-white">Volver</a></button>
        </div>
        <div class="d-flex mb-5">
            {!! $employees->links() !!}
        </div>

    </div>
@endsection
@section('js')
    <script src="{{ asset('js/confirmation.js') }}" defer></script>
@endsection
