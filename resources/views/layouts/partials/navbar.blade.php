<header class="">
    <nav class="">
        <div class="innerNav">
            <div class="logoMenu">
                <a href="{{ route('home.index') }}" class="">
                    <div class="logo">
                        <div class="imageLogo"><img src="{{ Storage::url('design/logoFixed.jpg') }}" alt="" class=""></div>
                        <div class="nameLogo">
                            <h3 class="">mayorago<br><span>asesores</span></h3>
                        </div>
                    </div>
                </a>
                <div class="menu">
                    <ul class="">
                        <li class=""><a class="" href="{{ route('home.index') }}">Inicio</a></li>
                        <li class=""><a class="" href="/#equipo">Equipo</a></li>
                        <li class=""><a class="" href="/#servicios">Servicios</a></li>
                        <li class=""><a class="" href="/#noticias">De interés</a></li>
                        <li class=""><a class="" href="/#contacto">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="intranetMenu">
                <div class="innerIntranetMenu">
                    <button class="intranetMenuBtn" onclick="openMenuIntranet()" type="button">Área de clientes</button>
                    @auth
                    @role('admin')
                    <div class="dropdownIntranet" id="myDropdownIntranet">
                        <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                        <hr><br>
                        <a href="{{ route('intranet.index') }}" class="dropdown-item">Intranet</a>
                        <a class="dropdown-item" href="{{ route('user.editData') }}">Panel usuario</a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                    </div>
                    @endrole
                    @role('user')
                    <div class="dropdownIntranet" id="myDropdownIntranet">
                        <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                        <hr><br>
                        <a href="{{ route('intranet.index') }}" class="dropdown-item">Intranet</a>
                        <a class="dropdown-item" href="{{ route('user.editData') }}">Panel usuario</a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                    </div>
                    @endrole
                    @role('asesor')
                    <div class="dropdownIntranet" id="myDropdownIntranet">
                        <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                        <hr><br>
                        <a href="{{ route('intranet.index') }}" class="dropdown-item">Intranet</a>
                        <a class="dropdown-item" href="{{ route('user.editData') }}">Panel usuario</a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                    </div>
                    @endrole
                    @role('contable')
                    <div class="dropdownIntranet" id="myDropdownIntranet">
                        <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                        <hr><br>
                        <a href="{{ route('intranet.index') }}" class="dropdown-item">Intranet</a>
                        <a class="dropdown-item" href="{{ route('user.editData') }}">Panel usuario</a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                    </div>
                    @endrole

                    @endauth

                    @guest
                    <div class="dropdownIntranet" id="myDropdownIntranet">
                        <a href="{{ route('login.perform') }}" class="btn btn-outline-light me-2">Login</a>
                    </div>
                    @endguest
                </div>
            </div>
            <div class="mobileMenu">
                <div class="innerMobileMenu">
                    <button class="mobileMenuBtn" onclick="openMenu()" type="button"><img src="{{ Storage::url('design/mobileMenu.png') }}" alt="" class=""></button>
                    @auth
                    @role('admin')
                    <div class="dropdown" id="myDropdown">
                        <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                        <hr><br>
                        <a href="{{ route('intranet.index') }}" class="dropdown-item">Intranet</a>
                        <a class="dropdown-item" href="{{ route('user.editData') }}">Panel usuario</a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                        <hr><br>
                        <a class="" href="{{ route('home.index') }}">Inicio</a>
                        <a class="" href="/#equipo">Equipo</a>
                        <a class="" href="/#servicios">Servicios</a>
                        <a class="" href="/#noticias">De interés</a>
                        <a class="" href="/#contacto">Contacto</a>
                    </div>
                    @endrole
                    @role('user')
                    <div class="dropdown" id="myDropdown">
                        <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                        <hr><br>
                        <a href="{{ route('intranet.index') }}" class="dropdown-item">Intranet</a>
                        <a class="dropdown-item" href="{{ route('user.editData') }}">Panel usuario</a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                        <hr><br>
                        <a class="" href="{{ route('home.index') }}">Inicio</a>
                        <a class="" href="/#equipo">Equipo</a>
                        <a class="" href="/#servicios">Servicios</a>
                        <a class="" href="/#noticias">De interés</a>
                        <a class="" href="/#contacto">Contacto</a>
                    </div>
                    @endrole
                    @role('asesor')
                    <div class="dropdown" id="myDropdown">
                        <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                        <hr><br>
                        <a href="{{ route('intranet.index') }}" class="dropdown-item">Intranet</a>
                        <a class="dropdown-item" href="{{ route('user.editData') }}">Panel usuario</a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                        <hr><br>
                        <a class="" href="{{ route('home.index') }}">Inicio</a>
                        <a class="" href="/#equipo">Equipo</a>
                        <a class="" href="/#servicios">Servicios</a>
                        <a class="" href="/#noticias">De interés</a>
                        <a class="" href="/#contacto">Contacto</a>
                    </div>
                    @endrole
                    @role('contable')
                    <div class="dropdown" id="myDropdown">
                        <p class="logued">Bienvenido {{ auth()->user()->name }}</p>
                        <hr><br>
                        <a href="{{ route('intranet.index') }}" class="dropdown-item">Intranet</a>
                        <a class="dropdown-item" href="{{ route('user.editData') }}">Panel usuario</a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">Salir</a>
                        <hr><br>
                        <a class="" href="{{ route('home.index') }}">Inicio</a>
                        <a class="" href="/#equipo">Equipo</a>
                        <a class="" href="/#servicios">Servicios</a>
                        <a class="" href="/#noticias">De interés</a>
                        <a class="" href="/#contacto">Contacto</a>
                    </div>
                    @endrole

                    @endauth

                    @guest
                    <div class="dropdown" id="myDropdown">
                        <a href="{{ route('login.perform') }}" class="btn btn-outline-light me-2">Login</a>
                        <hr><br>
                        <a class="" href="{{ route('home.index') }}">Inicio</a>
                        <a class="" href="/#equipo">Equipo</a>
                        <a class="" href="/#servicios">Servicios</a>
                        <a class="" href="/#noticias">De interés</a>
                        <a class="" href="/#contacto">Contacto</a>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
        </div>
    </nav>
</header>