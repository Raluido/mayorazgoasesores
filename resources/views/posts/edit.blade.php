@extends('layouts.app-master')

@section('content')
<section class="noticiasIntranetEdit">
    <div class="innerNoticiasIntranetEdit paddingFix">
        <div class="top">
            <h1 class="">Editor de Noticias</h1>
            <h3 class="">Aqui podrás hacer modificaciones a las noticias que ya has subido</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif
                <form action="/posts/{{ $post->id }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="inputForm">
                        <label for="">Si vas a añadir un enlace, marca esta opción</label>
                        <input type="checkbox" name="link" class="form-control" value="{{ $post->link }}" <?php if($post->link == 1) : echo 'checked'; endif ?> style="width:unset; display:unset;">
                    </div>
                    <div class="inputForm">
                        <label for="">Título</label>
                        <input type="text" name="title" value="{{ $post->title }}">
                    </div>
                    <div class="inputForm">
                        <label for="">Subtítulo</label>
                        <input type="text" name="subtitle" value="{{ $post->subtitle }}">
                    </div>
                    <div class="inputForm">
                        <label for="">Cuerpo</label>
                        <textarea name="body" id="" cols="70" rows="15">{{ $post->body }}</textarea>
                    </div>

                    <div class="inputForm">
                        <label for="">Publicado en fecha</label>
                        <input type="date" name="published_at" value="{{ date('Y-m-d', strtotime($post->published_at)) }}">
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
                        <button type="submit" class="stylingButtons green buttonTextWt">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection