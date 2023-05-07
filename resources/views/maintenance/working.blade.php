@extends('layouts.maintenance')

@section('content')

<section class="maintenance">
    <div class="innerMaintenance">
        <div class="top">
            <div class="innerTop">
                <h4 class="">Estamos haciendo tareas de mantenimiento, en breve estaremos en linea nuevamente.<br>Disculpe las molestias.</h4>
            </div>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <div class="logo">
                    <a href="{{ route('home.index') }}" class="innerLogo">
                        <div class=""><img src="{{ Storage::url('design/logoFixed.jpg') }}" alt="" class=""></div>
                        <h3 class="">mayorago<br><span>asesores</span></h3>
                    </a>
                </div>
                <div class="contactData">
                    <div class="innerContactData">
                        <h4 class="">
                            C/ Pilar Monteverde, 32<br>Urbanización El Mayorazgo<br>38300 La Orotava, Tenerife.<br>
                        </h4>
                        <div class="iconContact">
                            <div class="innerIconContact"><img src="{{ Storage::url('design/iconoContacto.jpg') }}" alt="" class=""></div>
                        </div>
                        <a href="https://wa.me/34618299310">
                            <h4 class="">
                                Móvil. 618 29 93 10
                            </h4>
                        </a>
                        <a href="922330025" class="">
                            <h4 class="">
                                Tfno. 922 33 00 25
                            </h4>
                        </a>
                        <a href="mailto:info@mayorazgoasesores.es" class="">
                            <h4 class="">info@mayorazgoasesores.es
                            </h4>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection