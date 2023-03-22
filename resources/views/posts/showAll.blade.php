@extends('layouts.app-master')

@section('content')
<section class="noticiasAll">
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
                    <p>{{ $post->body }}</p>
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