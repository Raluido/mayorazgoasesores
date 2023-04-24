@extends('layouts.app-master')

@section('content')

<section class="contacto" id="contacto">
    <div class="innerContacto">
        <div class="top">
            <h4 class="">Estamos haciendo tareas de mantenimiento, en breve estaremos en linea nuevamente.<br>Disculpe las molestias.</h4>
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
                    <h4 class="">
                        C/ Pilar Monteverde, 32<br>Urbanización El Mayorazgo<br>38300 La Orotava, Tenerife.<br>
                        <div class="iconContact">
                            <div class=""><img src="{{ Storage::url('design/iconoContacto.jpg') }}" alt="" class=""></div>
                        </div>
                        Tfno. 922 33 00 25<br>info@mayorazgoasesores.es
                    </h4>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection