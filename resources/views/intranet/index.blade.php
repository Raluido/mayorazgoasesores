@extends('layouts.app-master')

@section('content')
<section class="intranet">
    @auth
    <div class="innerIntranet">
        <div class="">
            @include('layouts.partials.messages')
        </div>
        @role('asesor')
        <h3>Área de administración</h3>
        <div class="areasIntranet">
            <div class="">
                <h5>Gestión de empresas</h5>
                <hr><br>
                <ul class="">
                    <li class=""><a class="" href="{{ route('users.index') }}">Listar empresas</a></li>
                    <li class=""><a class="" href="{{ route('employees.index') }}">Listar empleados</a></li>
                </ul>
            </div>
            <div class="">
                <h5>Nóminas</h5>
                <hr><br>
                <ul class="">
                    <li class=""><a href="{{ route('payrolls.uploadForm') }}" class="">Subir nóminas</a></li>
                    <li class=""><a href="{{ route('payrolls.showForm') }}" class="">Listar nóminas</a></li>
                </ul>
            </div>
            <div class="">
                <h5>Modelo de Imputación de Costes</h5>
                <hr><br>
                <ul class="">
                    <li class="">
                        <a href="{{ route('costsimputs.uploadForm') }}" class="">Subir imputaciones de costes</a>
                    </li>
                    <li class="">
                        <a href="{{ route('costsimputs.showForm') }}" class="">Listar imputaciones de costes</a>
                    </li>
                </ul>
            </div>
            <div class="">
                <h5>Otros documentos</h5>
                <hr><br>
                <ul class="">
                    <li class="">
                        <a href="{{ route('othersdocuments.uploadForm') }}" class="">Subir documentos de interés</a>
                    </li>
                    <li class="">
                        <a href="{{ route('othersdocuments.showForm') }}" class="">Listar documentos de interés</a>
                    </li>
                </ul>
            </div>
            <!-- <div class="">
                <h5>Gestionar blog</h5>
                <hr><br>
                <ul class="">
                    <li class="">
                        <a href="posts.index" class="">Crear, editar y borrar</a>
                    </li>
                </ul>
            </div> -->
        </div>
        @endrole
        @role('admin')
        <h3>Área de administración</h3>
        <div class="areasIntranet">
            <div class="">
                <h5>Gestión de empresas</h5>
                <hr><br>
                <ul class="">
                    <li class="">
                        <a class="" href="{{ route('users.index') }}">Listar empresas</a>
                    </li>
                    <li class="">
                        <a class="" href="{{ route('employees.index') }}">Listar empleados</a>
                    </li>
                </ul>
            </div>
            <div class="">
                <h5>Nóminas</h5>
                <hr><br>
                <ul class="">
                    <li class="">
                        <a href="{{ route('payrolls.uploadForm') }}" class="">Subir nóminas</a>
                    </li>
                    <li class="">
                        <a href="{{ route('payrolls.showForm') }}" class="">Listar nóminas</a>
                    </li>
                </ul>
            </div>
            <div class="">
                <h5>Modelo de Imputación de Costes</h5>
                <hr><br>
                <ul class="">
                    <li class="">
                        <a href="{{ route('costsimputs.uploadForm') }}" class=""> Subir documentos de Imputación de costes y otras</a>
                    </li>
                    <li class="">
                        <a href="{{ route('costsimputs.showForm') }}" class=""> Listar documentos de Imputación de costes y otras</a>
                    </li>
                </ul>
            </div>
            <div class="">
                <h5>Otros documentos</h5>
                <hr><br>
                <ul class="">
                    <li class="">
                        <a href="{{ route('othersdocuments.uploadForm') }}" class="">Subir documentos de interés</a>
                    </li>
                    <li class="">
                        <a href="{{ route('othersdocuments.showForm') }}" class="">Listar documentos de interés</a>
                    </li>
                </ul>
            </div>
            <div class="">
                <h5>Gestión de usuarios</h5>
                <hr><br>
                <ul class="">
                    <li class="">
                        <a class="dropdown-item" href="{{ route('users.index') }}">Usuarios</a>
                    </li>
                    <li class="">
                        <a class="dropdown-item" href="{{ route('roles.index') }}">Roles</a>
                    </li>
                    <li class="">
                        <a class="dropdown-item" href="{{ route('permissions.index') }}">Permisos</a>
                    </li>
                </ul>
            </div>
            <!-- <div class="">
                <h5>Gestionar blog</h5>
                <hr><br>
                <ul class="">
                    <li class="">
                        <a href="posts.index" class="">Crear, editar y borrar</a>
                    </li>
                </ul>
            </div> -->
        </div>
        @endrole
        @role('user')
        <h3>Área de descargas de empresas</h3>
        <div class="areasIntranet">
            <div class="">
                <h5>Nóminas</h5>
                <hr><br>
                <ul class="">
                    <li class="">
                        <a href="{{ route('payrolls.downloadForm') }}" class="">Nóminas</a>
                    </li>
                </ul>
            </div>
            <div class="">
                <h5>Documentos de interés</h5>
                <hr><br>
                <ul class="">
                    <li class="">
                        <a href="{{ route('othersdocuments.downloadForm') }}" class="">Otros documentos</a>
                    </li>
                </ul>
            </div>
            <div class="">
                <h5>Imputaciones de
                    costes</h5>
                <hr><br>
                <ul class="">
                    <li class="">
                        <a href="{{ route('costsimputs.downloadForm') }}" class="">Imputaciones de costes</a>
                    </li>
                </ul>
                @endrole
            </div>
        </div>
        @endauth
        @guest
        <div class="guestIntranet">
            <div class="">
                <h1>Bienvenido a Mayorazgo Asesores</h1>
            </div>
            <hr><br>
            <div class="">
                <p class="">Por favor, acceda con el usuario y la contraseña que le hemos facilitado.</p>
            </div>
        </div>
        @endguest
    </div>
    @endsection
</section>