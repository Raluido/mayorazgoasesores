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
                @if($post->subtitle != null)
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
                    <hr>
                    <div class="date">
                        <h5>Publicado en: {{ date('Y-m-d', strtotime($post->published_at)) }}</h5>
                    </div>
                    <div class="gotoNoticia">
                        <button class="gray stylingButtons"><a href="{{ route('posts.show', $post->id) }}" class="buttonTextWt">Ir a noticia</a></button>
                    </div>
                </div>
                @else
                @php
                libxml_use_internal_errors(true);
                $doc = new \DomDocument();
                $doc->loadHTML(mb_convert_encoding(file_get_contents($post->body), 'HTML-ENTITIES', 'UTF-8'));
                $xpath = new \DOMXPath($doc);
                $query = '//*/meta[starts-with(@property, \'og:\')]';
                $metas = $xpath->query($query);
                $title = ($metas[0]->getAttribute('content'));
                $description = ($metas[1]->getAttribute('content'));
                $image = ($metas[3]->getAttribute('content'));
                @endphp
                <div class="link">
                    <div class="innerLink">
                        <div class="linkImage">
                            <a href="{{ $post->body }}" target="_blank" class="">
                                <img src="{{ $image }}" alt="" class="">
                            </a>
                        </div>
                        <h3 class="">{{ $title }}</h3>
                        <p class="">{{ $description }}</p>
                    </div>
                    <hr>
                    <div class="date">
                        <h5>Publicado en: {{ date('Y-m-d', strtotime($post->published_at)) }}</h5>
                    </div>
                    <br>
                    <div class="gotoNoticia">
                        <button class="gray stylingButtons"><a href="{{ $post->body }}" target="_blank" class="buttonTextWt">Ir a noticia</a></button>
                    </div>
                </div>
                @endif
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