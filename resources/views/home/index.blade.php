@extends('layouts.app-master')

@section('content')

<section class="bienvenidos">
    <div class="top">
        <img src="{{ Storage::url('design/bienvenidos.jpg') }}" alt="" class="">
    </div>
    <div class="bottom">
        <p class="">
            <span style="font-weight:bold">Mayorazgo Asesores</span> es una <span style="font-weight:bold">Asesoría Jurídica-Laboral</span> con mas de 20 años de experiencia en el sector,<br> con un objetivo claro, dar <span style="font-style:bold">tranquilidad y soluciones</span> día a día a nuestros clientes.<br>
            Nuestro gran aval es el compromiso con nuestros clientes, la rápida respuesta y el aprendizaje continuo,<br> siendo en todo momento nuestros servicios gestionados por <span style="font-weight:bold">profesionales colegiados en cada materia.</span><br>
            Colaboramos estrechamente con asesorías fiscales y contables, sumando así garantías de profesionalidad,<br> ya que esa es sin duda nuestra misión cada día: ayudar y dar al cliente una solución rápida y profesional.
        </p>
    </div>
</section>
<section class="equipo" id="equipo">
    <div class="innerEquipo">
        <div class="top">
            <img src="{{ Storage::url('design/equipo.jpg') }}" alt="" class="">
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <div class="">
                    <img src="{{ Storage::url('design/equipo/Fran.jpg') }}" alt="" class="">
                    <h3 class="">Fran Luis</h3>
                    <h4 class="">Graduado Social</h4>
                </div>
                <div class="">
                    <img src="{{ Storage::url('design/equipo/Cande.jpg') }}" alt="" class="">
                    <h3 class="">Cande Rguez.</h3>
                    <h4 class="">Experta en Gestión Laboral</h4>
                </div>
                <div class="">
                    <img src="{{ Storage::url('design/equipo/Nieves.jpg') }}" alt="" class="">
                    <h3 class="">Nieves Moleiro</h3>
                    <h4 class="">Administrativa Laboral</h4>
                </div>
                <div class="">
                    <img src="{{ Storage::url('design/equipo/Ana.jpg') }}" alt="" class="">
                    <h3 class="">Ana Ledesma</h3>
                    <h4 class="">Abogada Civil y Penal</h4>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="servicios" id="servicios">
    <div class="innerServicios">
        <div class="top">
            <img src="{{ Storage::url('design/servicios.jpg') }}" alt="" class="">
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <div class="laboral">
                    <div class="iconText">
                        <div class="">
                            <img src="{{ Storage::url('design/iconoLaboral.jpg') }}" alt="" class="">
                            <h4 class="">Asesoría Laboral</h4>
                        </div>
                    </div>
                    <ul class="dashed">
                        <li class="">
                            Asesoramiento continuado y atención a consultas de carácter laboral y de prevención de riesgos laborales.
                        </li>
                        <li class="">
                            Análisis del impacto en la empresa de los cambios en la normativa laboral.
                        </li>
                        <li class="">
                            Estudio de las diferentes opciones de contratos laborales a realizar y bonificaciones aplicables.
                        </li>
                        <li class="">
                            Gestiones del autónomo con la Seguridad Social.
                        </li>
                        <li class="">
                            Atención a requerimientos, gestiones y procedimientos de revisión e inspección laboral derivada de actuaciones iniciadas por este despacho.
                        </li>
                        <li class="">
                            Elaboración de nóminas y seguros sociales.
                        </li>
                        <li class="">
                            Estudio y elaboración de despidos y finiquitos.
                        </li>
                        <li class="">
                            Tramitación de partes de baja y alta de enfermedad y accidentes de trabajo.
                        </li>
                        <li class="">
                            Emisión de certificados de estar al corriente.
                        </li>
                        <li class="">
                            Certificados de empresa para el desempleo y de retenciones para trabajadores y profesionales.
                        </li>
                    </ul>
                </div>
                <div class="juridica">
                    <div class="iconText">
                        <div class="">
                            <img src="{{ Storage::url('design/iconoJuridica.jpg') }}" alt="" class="">
                            <h4 class="">Asesoría Jurídica</h4>
                        </div>
                    </div>
                    <ul class="dashed">
                        <li class="">
                            Litigiosidad Laboral y Seguridad Social, incluyendo la asistencia jurídica ante órganos administrativos y representación procesal ante los órganos jurisdiccionales.
                        </li>
                        <li class="">
                            Reclamaciones frente a las Administraciones Públicas, particularmente frente a las resoluciones de la Administración Tributaria, Seguridad Social o SEPE.
                        </li>
                        <li class="">
                            Derecho Civil y de Familia, especialmente en asesoramiento de gestión patrimonial familiar y representación en procedimientos de Sucesiones y Donaciones, así como en reclamaciones procesales de impagos.
                        </li>
                        <li class="">
                            Defensa en el Orden Penal.
                        </li>
                    </ul>
                </div>
                <div class="fiscal">
                    <div class="iconText">
                        <div class="">
                            <img src="{{ Storage::url('design/iconoFiscal.jpg') }}" alt="" class="">
                            <h4 class="">Asesoría Fiscal</h4>
                        </div>
                    </div>
                    <ul class="dashed">
                        <li class="">
                            Declaración modelo 100 del IRPF (Renta).
                        </li>
                        <li class="">
                            Análisis y elaboración del modelo 111 y 190 (retenciones e ingresos a cuenta del IRPF).
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="noticias" id="noticias">
    <div class="innerNoticias">
        <div class="top">
            <img src="{{ Storage::url('design/noticias.jpg') }}" alt="" class="">
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <div class="subtitle">
                    <h3>Ponte al día con las noticias que te interesan</h3>
                </div>
                @if(empty($posts[0]))
                <div class="noNews">
                    <p>Por ahora no hay noticias subidas, pronto tendremos la actualidad!!</p>
                </div>
                @else
                @foreach ($posts as $post)
                <div class="news">
                    <h3>{{ $post->title }}</h3>
                    <hr>
                    <h5>{{ date('Y-m-d', strtotime($post->published_at)) }}</h5>
                    <br>
                    <p>{{Str::limit($post->body, 400) }}</p>
                    <div class="gotoNoticia">
                        <button class="gray stylingButtons"><a href="{{ route('posts.show', $post->id) }}" class="buttonTextWt">Ir a noticia</a></button>
                    </div>
                </div>
                @endforeach
                @endif
                <div class="bottomNav">
                    <button class="stylingButtons blue"><a href="{{ route('posts.showAll') }}" class="">Mostrar todas</a></button>
                </div>
            </div>
        </div>
</section>

<section class="contacto" id="contacto">
    <div class="innerContacto">
        <div class="top">
            <img src="{{ Storage::url('design/contacto.jpg') }}" alt="" class="">
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <div class="logo">
                    <a href="{{ route('home.index') }}" class="innerLogo">
                        <img src="{{ Storage::url('design/logoFixed.jpg') }}" alt="" class="">
                        <h3 class="">mayorago<br><span style="font-size: 1.3em">asesores</span></h3>
                    </a>
                </div>
                <div class="contactData">
                    <h5 class="">
                        C/ Pilar Monteverde, 32<br>Urbanización El Mayorazgo<br>38300 La Orotava, Tenerife.<br><br>Tfno. 922 33 00 25<br>info@mayorazgoasesores.es
                    </h5>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection