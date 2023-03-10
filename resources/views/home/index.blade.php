@extends('layouts.app-master')

@section('content')

<section class="bienvenidos">
    <div class="top">
        <img src="{{ Storage::url('design/bienvenidos.jpg') }}" alt="" class="">
    </div>
    <div class="bottom">
        <p class="">
            <span style="font-weight:bold">Mayorazgo Asesores</span> es una <span style="font-weight:bold">Asesoria Juridica-Laboral</span>, con mas de 20 aflos de experiencia en el sector, con un objetivo claro, dar <span style="font-style:bold">tranquilidad y soluciones</span> al día a día de nuestros clientes.
            Nuestro compromiso, aprendizaje continuo y rapida respuesta nos avalan, siendo en todo momento nuestros servicios gestionados por <span style="font-weight:bold">profesionales colegiados en cada materia.</span>
            Desde el inicio, Mayorazgo Asesores colabora estrechamente con varias asesorías fiscales y contables, garantizando a nuestros clientes en todo momento, que las colaboraciones que recomendamos, cumplen con todas las garantías de profesionalidad que exigimos para poder ofrecer estos servicios.
        </p>
    </div>
</section>
<section class="equipo">
    <div class="top">
        <img src="{{ Storage::url('design/equipo.jpg') }}" alt="" class="">
    </div>
    <div class="bottom">
        <div class="innerBottom">
            <img src="{{ Storage::url('design/equipo/Fran.jpg') }}" alt="" class="">
            <h3 class="">Fran Luis</h3>
            <h4 class="">Graduado Social</h4>
        </div>
        <div class="innerBottom">
            <img src="{{ Storage::url('design/equipo/Ana.jpg') }}" alt="" class="">
            <h3 class="">Fran Luis</h3>
            <h4 class="">Graduado Social</h4>
        </div>
        <div class="innerBottom">
            <img src="{{ Storage::url('design/equipo/Cande.jpg') }}" alt="" class="">
            <h3 class="">Fran Luis</h3>
            <h4 class="">Graduado Social</h4>
        </div>
        <div class="innerBottom">
            <img src="{{ Storage::url('design/equipo/Nieves.jpg') }}" alt="" class="">
            <h3 class="">Fran Luis</h3>
            <h4 class="">Graduado Social</h4>
        </div>
    </div>
</section>

@endsection