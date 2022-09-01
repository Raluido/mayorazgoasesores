<header class="">
    <nav class="navbar navbar-expand-lg navbar-light bg-color text-white p-3">
        <div class="container-fluid">
            <div class="d-flex">
                <a href="{{ route('home.index') }}" class="nav-link px-2 text-white"><span
                        class="fs-5 fw-bold">mayorazgo</span> <br> <span class="fs-4 fw-bold">asesores</span></a>
                <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item px-3"><a class="nav-link text-light fs-6"
                            href="{{ route('home.index') }}">Inicio</a></li>
                    <li class="nav-item px-3"><a class="nav-link text-light fs-6" href="{{ route('posts.showAll') }}">Noticias</a></li>
                    <li class="nav-item px-3"><a class="nav-link text-light fs-6" href="#">Sobre nosotros</a></li>
                    <li class="nav-item px-3"><a class="nav-link text-light fs-6" href="#">Contacto</a></li>
                </ul>
            </div>
            <div class="">
                @auth
                    @role('admin')
                        <div class="nav-item dropdown">
                            <button class="btn text-light dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Bienvenido {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="{{ route('users.index') }}">Usuarios</a></li>
                                <li><a class="dropdown-item" href="{{ route('roles.index') }}">Roles</a></li>
                                <li><a class="dropdown-item" href="{{ route('permissions.index') }}">Permisos</a>
                                </li>
                                <li><a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                                </li>
                            </ul>
                        </div>
                    @endrole
                    @role('user')
                        <div class="nav-item dropdown">
                            <button class="btn text-light dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Bienvenido {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                                </li>
                            </ul>
                        </div>
                    @endrole
                    @role('asesor')
                        <div class="nav-item dropdown">
                            <button class="btn text-light dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Bienvenido {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                                </li>
                            </ul>
                        </div>
                    @endrole
                    @role('contable')
                        <div class="nav-item dropdown">
                            <button class="btn text-light dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Bienvenido {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                                </li>
                            </ul>
                        </div>
                    @endrole


                @endauth

                @guest
                    <div class="text-end">
                        <a href="{{ route('login.perform') }}" class="btn btn-outline-light me-2">Área de clientes</a>
                        @role('admin')
                            <a href="{{ route('register.perform') }}" class="btn btn-warning">Sign-up</a>
                        @endrole
                    </div>
                @endguest
            </div>
        </div>
</header>
