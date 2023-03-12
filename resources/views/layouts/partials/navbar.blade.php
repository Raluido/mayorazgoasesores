<header class="">
    <nav class="">
        <div class="innerNav">
            <div class="logoMenu">
                <div class="logo">
                    <a href="{{ route('home.index') }}" class="innerLogo">
                        <img src="{{ Storage::url('design/logoFixed.jpg') }}" alt="" class="">
                        <h3 class="">mayorago<br><span style="font-size: 1.3em">asesores</span></h3>
                    </a>
                </div>
                <div class="menu">
                    <ul class="navbar-nav mx-auto">
                        <li class=""><a class="" href="{{ route('home.index') }}">Inicio</a></li>
                        <li class=""><a class="" href="">Equipo</a></li>
                        <li class=""><a class="" href="">Servicios</a></li>
                        <li class=""><a class="" href="{{ route('posts.showAll') }}">Noticias</a></li>
                        <li class=""><a class="" href="#">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="mobileMenu">
                <img src="{{ Storage::url('design/mobileMenu.png') }}" alt="" class=""><button class="innerMobileMenu" type="button"></button></>
            </div>
            <div class="intranet">
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
                    <button class="" type="button" id="dropdownMenuButton1">
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
                    <button class="" type="button" id="dropdownMenuButton1">
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
    </nav>
</header>