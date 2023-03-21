@extends('layouts.app-master')

@section('content')
<section class="showNoticia">
    <div class="innerShowNoticia">
        <div class="top">
            <h1>Noticias</h1>
            <h3 class="">Las noticias de actualidad</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <div class="">
                    <div class="">
                        @if (session('status'))
                        <div class="" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif
                        <div class="title">
                            {{ $post->title }}
                        </div>
                        <hr>
                        <div class="body">
                            <p class="">{{ $post->body }}</p>
                        </div>
                        <div class="date">
                            <h5>Publicado en: {{ date('Y-m-d', strtotime($post->published_at)) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a class="buttonTextWt" href="{{ route('intranet.index') }} ">Volver</a></button>
        </div>
    </div>
</section>
@endsection