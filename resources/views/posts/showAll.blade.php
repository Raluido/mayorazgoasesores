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
                @if($post->link == 0)
                <div class="news">
                    <a href="{{ route('posts.show', $post->id) }}" class="">
                        <h3>{{ $post->title }}</h3>
                        <p>{{ $post->subtitle }}</p>
                        <hr>
                        <div class="date">
                            <h5>Publicado en: {{ date('Y-m-d', strtotime($post->published_at)) }}</h5>
                        </div>
                        <br>
                    </a>
                    <!-- <div class="gotoNoticia">
                        <button class="gray stylingButtons"><a href="{{ route('posts.show', $post->id) }}" target="_blank" class="buttonTextWt">Ir a noticia</a></button>
                    </div> -->
                </div>
                @else
                @php
                $opts = [
                'http' => [
                'timeout' => 5, // 5 seconds
                ]
                ];
                $context = stream_context_create($opts);
                libxml_set_streams_context($context);
                libxml_use_internal_errors(true);
                $doc = new \DomDocument();
                try {
                $doc->loadHTML(mb_convert_encoding(file_get_contents($post->body), 'HTML-ENTITIES', "UTF-8"));
                } catch (\Throwable $th) {
                echo '<div class="red">Ha habido un error, compruebe si selecciono correctamente entre link o noticia</div>';
                }
                $xpath = new \DOMXPath($doc);
                $queryTitle = '//*/meta[starts-with(@property, \'og:title\')]';
                $queryDescription = '//*/meta[starts-with(@property, \'og:description\')]';
                $queryImage = '//*/meta[starts-with(@property, \'og:image\')]';
                $metaTitle = "";
                $metaDescription = "";
                $metaImage = "";
                $metaTitle = $xpath->query($queryTitle);
                $metaDescription = $xpath->query($queryDescription);
                $metaImage = $xpath->query($queryImage);
                if(!$metaTitle[0] == ""){
                $title = $metaTitle[0]->getAttribute('content');
                } else {
                $title = $post->title;
                }
                if(!$metaDescription[0] == ""){
                $description = $metaDescription[0]->getAttribute('content');
                } else {
                $description = $post->subtitle;
                }
                if(!$metaImage[0] == ""){
                $image = $metaImage[0]->getAttribute('content');

                try {
                $checkImage = getimagesize($image);
                } catch (\Throwable $th) {
                $checkImage = "";
                }

                } else {
                $image = "";
                }
                @endphp
                <div class="link">
                    <a href="{{ $post->body }}" target="_blank" class="">
                        <div class="innerLink">
                            <div class="linkImage">
                                @if(!$image == "" && $checkImage != "")
                                <img src="{{ $image }}" alt="" class="">
                                @endif
                            </div>
                            <h3 class="">{{ $title }}</h3>
                            <p class="">{{ $description }}</p>
                        </div>
                        <hr>
                        <div class="date">
                            <h5>Publicado en: {{ date('Y-m-d', strtotime($post->published_at)) }}</h5>
                        </div>
                        <br>
                    </a>
                    <!-- <div class="gotoNoticia">
                        <button class="gray stylingButtons"><a href="{{ $post->body }}" target="_blank" class="buttonTextWt">Ir a noticia</a></button>
                    </div> -->
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