@extends('layouts.app-master')

@section('content')
<div class="bg-light mt-4 rounded">
    @auth
    <div class="container">
        <div class="row w-75 gy-2 mx-auto">
            @role('asesor')
            <h3>Área de administración</h3>
            <div class="border col-12" style="height: fit-content">
                <h5>Gestión de empresas</h5>
                <a class="nav-link px-2 text-dark" href="{{ route('users.index') }}">Listar empresas</a>
                <a class="nav-link px-2 text-dark" href="{{ route('employees.index') }}">Listar empleados</a>
            </div>
            <div class="border col-12" style="height: fit-content">
                <h5>Nóminas</h5>
                <a href="{{ route('payrolls.uploadForm') }}" class="nav-link px-2 text-dark">Subir nóminas</a>
                <a href="{{ route('payrolls.showForm') }}" class="nav-link px-2 text-dark">Listar nóminas</a>
            </div>
            <div class="border col-12" style="height: fit-content">
                <h5>Modelo de Imputación de Costes</h5>
                <a href="{{ route('costsimputs.uploadForm') }}" class="nav-link px-2 text-dark">Subir imputaciones de
                    costes</a>
                <a href="{{ route('costsimputs.showForm') }}" class="nav-link px-2 text-dark">Listar imputaciones de
                    costes</a>
            </div>
            <div class="border py-4 col-12" style="height: fit-content">
                <h5>Otros documentos</h5>
                <a href="{{ route('othersdocuments.uploadForm') }}" class="nav-link px-2 text-dark">Subir documentos de
                    interés</a>
                <a href="{{ route('othersdocuments.showForm') }}" class="nav-link px-2 text-dark">Listar documentos de
                    interés</a>
            </div>
            <div class="border col-12" style="height: fit-content">
                <h5>Gestionar blog</h5>
                <a href="{{ route('home.index') }}" class="nav-link px-2 text-dark">Crear, editar y borrar</a>
            </div>
            @endrole
            @role('admin')
            <h3>Área de administración</h3>
            <div class="border py-4 col-12" style="height: fit-content">
                <h5>Gestión de empresas</h5>
                <a class="nav-link px-2 text-dark" href="{{ route('users.index') }}">Listar empresas</a>
                <a class="nav-link px-2 text-dark" href="{{ route('employees.index') }}">Listar empleados</a>
            </div>
            <div class="border py-4 col-12" style="height: fit-content">
                <h5>Nóminas</h5>
                <a href="{{ route('payrolls.uploadForm') }}" class="nav-link px-2 text-dark">Subir nóminas</a>
                <a href="{{ route('payrolls.showForm') }}" class="nav-link px-2 text-dark">Listar nóminas</a>
            </div>
            <div class="border py-4 col-12" style="height: fit-content">
                <h5>Modelo de Imputación de Costes</h5>
                <a href="{{ route('costsimputs.uploadForm') }}" class="nav-link px-2 text-dark"> Subir documentos de
                    Imputación de costes
                    y
                    otras</a>
                <a href="{{ route('costsimputs.showForm') }}" class="nav-link px-2 text-dark"> Listar
                    documentos de
                    Imputación de costes
                    y
                    otras</a>
            </div>
            <div class="border py-4 col-12" style="fit-content">
                <h5>Otros documentos</h5>
                <a href="{{ route('othersdocuments.uploadForm') }}" class="nav-link px-2 text-dark">Subir documentos de
                    interés</a>
                <a href="{{ route('othersdocuments.showForm') }}" class="nav-link px-2 text-dark">Listar documentos de
                    interés</a>
            </div>
            <div class="border col-12" style="fit-content">
                <h5>Gestionar blog</h5>
                <a href="{{ route('home.index') }}" class="nav-link px-2 text-dark">Crear, editar y borrar</a>
            </div>
            @endrole
        </div>
        <div class="row w-75 mt-3 gy-4 mx-auto">
            @role('user')
            <h3>Área de descargas de empresas</h3>
            <div class="border col-4" style="fit-content">
                <h5>Nóminas</h5>
                <a href="{{ route('payrolls.downloadForm') }}" class="nav-link px-2 text-dark">Nóminas</a>
            </div>
            <div class="border col-4" style="fit-content">
                <h5>Documentos de interés</h5>
                <a href="{{ route('othersdocuments.downloadForm') }}" class="nav-link px-2 text-dark">Otros
                    documentos</a>
            </div>
            <div class="border col-4" style="fit-content">
                <h5>Imputaciones de
                    costes</h5>
                <a href="{{ route('costsimputs.downloadForm') }}" class="nav-link px-2 text-dark">Imputaciones de
                    costes</a>
                @endrole
            </div>
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
    </div>
</div>