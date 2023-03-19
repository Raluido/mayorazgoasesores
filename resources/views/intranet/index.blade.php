@extends('layouts.app-master')

@section('content')
<section class="intranet">
    @auth
    <div class="innerIntranet">
        <div class="roleIntranet">
            @role('asesor')
            <h3>Área de administración</h3>
            <div class="">
                <h5>Gestión de empresas</h5>
                <a class="" href="{{ route('users.index') }}">Listar empresas</a>
                <a class="" href="{{ route('employees.index') }}">Listar empleados</a>
            </div>
            <div class="">
                <h5>Nóminas</h5>
                <a href="{{ route('payrolls.uploadForm') }}" class="">Subir nóminas</a>
                <a href="{{ route('payrolls.showForm') }}" class="">Listar nóminas</a>
            </div>
            <div class="">
                <h5>Modelo de Imputación de Costes</h5>
                <a href="{{ route('costsimputs.uploadForm') }}" class="">Subir imputaciones de
                    costes</a>
                <a href="{{ route('costsimputs.showForm') }}" class="">Listar imputaciones de
                    costes</a>
            </div>
            <div class="">
                <h5>Otros documentos</h5>
                <a href="{{ route('othersdocuments.uploadForm') }}" class="">Subir documentos de
                    interés</a>
                <a href="{{ route('othersdocuments.showForm') }}" class="">Listar documentos de
                    interés</a>
            </div>
            <div class="">
                <h5>Gestionar blog</h5>
                <a href="{{ route('posts.index') }}" class="">Crear, editar y borrar</a>
            </div>
            @endrole
            @role('admin')
            <h3>Área de administración</h3>
            <div class="">
                <h5>Gestión de empresas</h5>
                <a class="" href="{{ route('users.index') }}">Listar empresas</a>
                <a class="" href="{{ route('employees.index') }}">Listar empleados</a>
            </div>
            <div class="">
                <h5>Nóminas</h5>
                <a href="{{ route('payrolls.uploadForm') }}" class="">Subir nóminas</a>
                <a href="{{ route('payrolls.showForm') }}" class="">Listar nóminas</a>
            </div>
            <div class="">
                <h5>Modelo de Imputación de Costes</h5>
                <a href="{{ route('costsimputs.uploadForm') }}" class=""> Subir documentos de
                    Imputación de costes
                    y
                    otras</a>
                <a href="{{ route('costsimputs.showForm') }}" class=""> Listar
                    documentos de
                    Imputación de costes
                    y
                    otras</a>
            </div>
            <div class="">
                <h5>Otros documentos</h5>
                <a href="{{ route('othersdocuments.uploadForm') }}" class="">Subir documentos de
                    interés</a>
                <a href="{{ route('othersdocuments.showForm') }}" class="">Listar documentos de
                    interés</a>
            </div>
            <div class="">
                <h5>Gestionar blog</h5>
                <a href="{{ route('home.index') }}" class="">Crear, editar y borrar</a>
            </div>
            @endrole
        </div>
        <div class="">
            @role('user')
            <h3>Área de descargas de empresas</h3>
            <div class="">
                <h5>Nóminas</h5>
                <a href="{{ route('payrolls.downloadForm') }}" class="">Nóminas</a>
            </div>
            <div class="">
                <h5>Documentos de interés</h5>
                <a href="{{ route('othersdocuments.downloadForm') }}" class="">Otros
                    documentos</a>
            </div>
            <div class="">
                <h5>Imputaciones de
                    costes</h5>
                <a href="{{ route('costsimputs.downloadForm') }}" class="">Imputaciones de
                    costes</a>
                @endrole
            </div>
            @endauth
            @guest
            <div class="">
                <h1>Bienvenido a Mayorazgo Asesores</h1>
            </div>
            <div class="">
                <p class="">Por favor, acceda con el usuario y la contraseña que le hemos facilitado.</p>
            </div>
            @endguest
        </div>
        @endsection
    </div>
</section>