@extends('layouts.app-master')

@section('content')
<div class="employees">
    <div class="innerEmployees paddingFix">
        <div class="top">
            <h1>Empleados</h1>
        </div>
        <div class="bottom">
            <div class="addDelButtons">
                <button class="stylingButtons green"><a href="{{ route('employees.create') }}" class="buttonTextWt">Añadir nuevo empleado</a>
                    <a class="d-none" id="confirmationBtn" href="{{ url('employees/deleteAll') }}"></a>
                    <button class="stylingButtons red buttonTextWt" onclick="confirmation()">Eliminar
                        todos</button>
            </div>
            <div class="">
                @include('layouts.partials.messages')
            </div>
            <table class="">
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
                        <td><button class="stylingButtons blue"><a href="{{ route('employees.show', $index->id) }}" class="buttonTextWt">Mostrar</a></button>
                            <button class="stylingButtons green"><a href="{{ route('employees.edit', $index->id) }}" class="buttonTextWt">Editar</a></button>
                            {{ html()->form('DELETE', '/employees/' . $index->id . '/delete')->open() }}
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
        </div>
        <div class="">
            {!! $employees->links() !!}
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('js/confirmation.js') }}" defer></script>
@endsection