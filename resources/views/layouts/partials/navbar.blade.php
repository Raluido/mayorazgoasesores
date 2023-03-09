<header class="">
    <nav class="">
        <div class="innerNav" style="width:100%">
            <div class="logo">
                <a href="{{ route('home.index') }}" class="innerLogo">
                    <img src="{{ Storage::url('design/logo.png') }}" alt="" class="">
                    <h3 class="">mayorago<br>asesores</h3>
                </a>
                <button class="" type="button">
                    <span class=""></span>
                </button>
            </div>
            <div class="menu">
                <ul class="navbar-nav mx-auto">
                    <li class=""><a class="" href="{{ route('home.index') }}">Inicio</a></li>
                    <li class=""><a class="" href="">Equipo</a></li>
                    <li class=""><a class="" href="">Servicios</a></li>
                    <li class=""><a class="" href="{{ route('posts.showAll') }}">Noticias</a></li>
                    <li class=""><a class="" href="#">Contacto</a></li>
                </ul>
                <div class="">
                    @auth
                    @role('admin')
                    <div class="">
                        <button class="" type="button" id="dropdownMenuButton1">
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
                        <button class="" type="button" id="dropdownMenuButton1">
                            Bienvenido {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li> <a class="dropdown-item" href="{{ route('user.editData') }}">Panel usuario</a>
                            </li>
                            <li><a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                            </li>
                        </ul>
                    </div>
                    @endrole
                    @role('asesor')
                    <div class="nav-item dropdown">
                        <button class="btn text-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
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
                        <button class="btn text-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
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
        </div>
    </nav>
</header>