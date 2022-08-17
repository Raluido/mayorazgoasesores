@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-3 mt-5 rounded">
        @auth
            <div class="">
                <h1>Mayorazgo Asesores - Administración</h1>
            </div>
            <div class="mt-2">
                <p class="lead">Sólo usuarios administradores pueden acceder a ésta sección.</p>
            </div>
            <div class="container">
                <div class="row w-75 mt-3 gy-4 mx-auto">
                    <div class="col-6 p-3">
                        @role('admin')
                            <div class="d-flex justify-content-center align-items-center border" style="height: 110px">
                                <a href="{{ route('payrolls.uploadForm') }}" class="nav-link px-2 text-dark">Subir nóminas</a>
                            </div>
                        @endrole
                        @role('user')
                            <div class="d-flex justify-content-center align-items-center border" style="height: 110px">
                                <a href="{{ route('payrolls.downloadForm') }}" class="nav-link px-2 text-dark">Descargar nóminas</a>
                            </div>
                        @endrole
                    </div>
                    <div class="col-6 p-3">
                        @role('admin')
                            <div class="d-flex justify-content-center align-items-center border" style="height: 110px">
                                <a href="{{ route('payrolls.showForm') }}" class="nav-link px-2 text-dark">Listar nóminas</a>
                            </div>
                        @endrole
                    </div>
                    <div class="col-6 p-3">
                        @role('admin')
                            <div class="d-flex justify-content-center align-items-center border" style="height: 110px">
                                <a href="" class="nav-link px-2 text-dark">Subir modelo 110</a>
                            </div>
                        @endrole
                        @role('user')
                            <div class="d-flex justify-content-center align-items-center border" style="height: 110px">
                                <a href="" class="nav-link px-2 text-dark">Descargar modelo 110</a>
                            </div>
                        @endrole
                    </div>
                    <div class="col-6 p-3">
                        @role('admin')
                            <div class="d-flex justify-content-center align-items-center border" style="height: 110px">
                                <a href="" class="nav-link px-2 text-dark">Subir modelo 111</a>
                            </div>
                        @endrole
                        @role('user')
                            <div class="d-flex justify-content-center align-items-center border" style="height: 110px">
                                <a href="" class="nav-link px-2 text-dark">Descargar modelo 111</a>
                            </div>
                        @endrole
                    </div>
                    <div class="col-6 p-3">
                        @role('admin')
                            <div class="d-flex justify-content-center align-items-center border" style="height: 110px">
                                <a href="" class="nav-link px-2 text-dark">Subir modelo 190</a>
                            </div>
                        @endrole
                        @role('user')
                            <div class="d-flex justify-content-center align-items-center border" style="height: 110px">
                                <a href="" class="nav-link px-2 text-dark">Descargar modelo 190</a>
                            </div>
                        @endrole
                    </div>
                    <div class="col-6 p-3">
                        @role('admin')
                            <div class="d-flex justify-content-center align-items-center border" style="height: 110px">
                                <a href="{{ route('users.addUsersAutoForm') }}" class="nav-link px-2 text-dark">Añadir empresas automaticamente</a>
                            </div>
                        @endrole
                    </div>

                </div>

            </div>

            {{-- <a href="{{ route('payrolls.generatePayrolls') }}" class="nav-link px-2 text-dark">Generar nóminas</a>
            <a href="{{ route('payrolls.index') }}" class="nav-link px-2 text-dark">Subir nóminas</a>
            <a href="{{ route('pdf.index') }}" class="nav-link px-2 text-dark">Convertir pdf</a> --}}

        @endauth

        @guest
            <div class="">
                <h1>Bienvenido a Mayorazgo Asesores</h1>
            </div>
            <div class="mt-3">
                <p class="lead">Por favor, acceda con el usuario y la contraseña que le hemos facilitado.</p>
            </div>
        @endguest
    </div>
@endsection
