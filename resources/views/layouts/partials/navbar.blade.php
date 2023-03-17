<header class="">
    <nav class="">
        <div class="innerNav">
            <div class="logoMenu">
                <div class="logo">
                    <a href="{{ route('home.index') }}" class="innerLogo">
                        <img src="{{ Storage::url('design/logoFixed.jpg') }}" alt="" class="">
                        <h3 class="">mayorago<br><span>asesores</span></h3>
                    </a>
                </div>
                <div class="menu">
                    <ul class="">
                        <li class=""><a class="" href="{{ route('home.index') }}">Inicio</a></li>
                        <li class=""><a class="" href="#equipo">Equipo</a></li>
                        <li class=""><a class="" href="#servicios">Servicios</a></li>
                        <li class=""><a class="" href="#noticias">Noticias</a></li>
                        <li class=""><a class="" href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="intranet">
                    <div class="innerIntranet">
                        <button class="intranetMenuBtn" onclick="openMenuIntranet()" type="button">Intranet</button>
                        @auth
                        @role('admin')
                        <div class="dropdownIntranet" id="myDropdownIntranet">
                            <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                            <hr>
                            <a class="dropdown-item" href="{{ route('users.index') }}">Usuarios</a>
                            <a class="dropdown-item" href="{{ route('roles.index') }}">Roles</a>
                            <a class="dropdown-item" href="{{ route('permissions.index') }}">Permisos</a>
                            <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                        </div>
                        @endrole
                        @role('user')
                        <div class="dropdownIntranet" id="myDropdownIntranet">
                            <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                            <hr>
                            <a class="dropdown-item" href="{{ route('user.editData') }}">Panel usuario</a>
                            <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                        </div>
                        @endrole
                        @role('asesor')
                        <div class="dropdownIntranet" id="myDropdownIntranet">
                            <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                            <hr>
                            <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                        </div>
                        @endrole
                        @role('contable')
                        <div class="dropdownIntranet" id="myDropdownIntranet">
                            <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                            <hr>
                            <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                        </div>
                        @endrole

                        @endauth

                        @guest
                        <div class="dropdownIntranet" id="myDropdownIntranet">
                            <a href="{{ route('login.perform') }}" class="btn btn-outline-light me-2">Login</a>
                            @role('admin')
                            <a href="{{ route('register.perform') }}" class="btn btn-warning">Registrar</a>
                            @endrole
                        </div>
                        @endguest
                    </div>
                </div>
            </div>
            <div class="mobileMenu">
                <div class="innerMobileMenu">
                    <button class="mobileMenuBtn" onclick="openMenu()" type="button"><img src="{{ Storage::url('design/mobileMenu.png') }}" alt="" class=""></button>
                    @auth
                    @role('admin')
                    <div class="dropdown" id="myDropdown">
                        <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                        <hr>
                        <a class="" href="{{ route('home.index') }}">Inicio</a>
                        <a class="" href="#equipo">Equipo</a>
                        <a class="" href="#servicios">Servicios</a>
                        <a class="" href="#noticias">Noticias</a>
                        <a class="" href="#contacto">Contacto</a>
                        <a class="dropdown-item" href="{{ route('users.index') }}">Usuarios</a>
                        <a class="dropdown-item" href="{{ route('roles.index') }}">Roles</a>
                        <a class="dropdown-item" href="{{ route('permissions.index') }}">Permisos</a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                    </div>
                    @endrole
                    @role('user')
                    <div class="dropdown" id="myDropdown">
                        <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                        <hr>
                        <a class="" href="{{ route('home.index') }}">Inicio</a>
                        <a class="" href="#equipo">Equipo</a>
                        <a class="" href="#servicios">Servicios</a>
                        <a class="" href="#noticias">Noticias</a>
                        <a class="" href="#contacto">Contacto</a>
                        <a class="dropdown-item" href="{{ route('user.editData') }}">Panel usuario</a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                    </div>
                    @endrole
                    @role('asesor')
                    <div class="dropdown" id="myDropdown">
                        <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                        <hr>
                        <a class="" href="{{ route('home.index') }}">Inicio</a>
                        <a class="" href="#equipo">Equipo</a>
                        <a class="" href="#servicios">Servicios</a>
                        <a class="" href="#noticias">Noticias</a>
                        <a class="" href="#contacto">Contacto</a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                    </div>
                    @endrole
                    @role('contable')
                    <div class="dropdown" id="myDropdown">
                        <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                        <hr>
                        <a class="" href="{{ route('home.index') }}">Inicio</a>
                        <a class="" href="#equipo">Equipo</a>
                        <a class="" href="#servicios">Servicios</a>
                        <a class="" href="#noticias">Noticias</a>
                        <a class="" href="#contacto">Contacto</a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                    </div>
                    @endrole

                    @endauth

                    @guest
                    <div class="dropdown" id="myDropdown">
                        <a class="" href="{{ route('home.index') }}">Inicio</a>
                        <a class="" href="#equipo">Equipo</a>
                        <a class="" href="#servicios">Servicios</a>
                        <a class="" href="#noticias">Noticias</a>
                        <a class="" href="#contacto">Contacto</a>
                        <a href="{{ route('login.perform') }}" class="btn btn-outline-light me-2">Login</a>
                        @role('admin')
                        <a href="{{ route('register.perform') }}" class="btn btn-warning">Registrar</a>
                        @endrole
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
</header>