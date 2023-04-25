@extends('layouts.app-master')

@section('content')
<section class="noticiasAll">
    <div class="innerNoticias">
        <div class="top">
            <img src="{{ Storage::url('design/noticias.jpg') }}" alt="" class="">
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <div class="">
                    <h1>Todas las noticias</h1>
                </div>
                @if(empty($posts[0]))
                <div class="noNews">
                    <p>Por ahora no hay noticias subidas, pronto tendremos la actualidad!!</p>
                </div>
                @else
                @foreach ($posts as $post)
                <div class="news">
                    <div class="title">
                        <h1 class="">{{ $post->title }}</h1>
                    </div>
                    <div class="subtitle">
                        <p class="">{{ $post->subtitle }}</p>
                    </div>
                    <hr>
                    <div class="content">
                        <p class="">{!! nl2br(e($post->body))!!}</p>
                    </div>
                    <div class="date">
                        <h5>Publicado en: {{ date('Y-m-d', strtotime($post->published_at)) }}</h5>
                    </div>
                    <div class="gotoNoticia">
                        <button class="gray stylingButtons"><a href="{{ route('posts.show', $post->id) }}" class="buttonTextWt">Ir a noticia</a></button>
                    </div>
                </div>
                @endforeach
                @endif
                <div class="bottomNav">
                    <button class="stylingButtons blue"><a href="{{ route('home.index') }}" class="buttonTextWt">Volver</a></button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection