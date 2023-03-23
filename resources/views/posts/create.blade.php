@extends('layouts.app-master')

@section('content')
<section class="createNoticia">
    <div class="innerCreateNoticia">
        <div class="top">
            <h1>Noticias</h1>
            <h3 class="">Aqui puedes crear nuevas noticias.</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                @if (session('status'))
                <div class="" role="alert">
                    {{ session('status') }}
                </div>
                @endif
                <form action="/posts/store" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="inputForm">
                        <label for="">Título</label>
                        <input type="text" name="title" class="form-control">
                    </div>
                    <div class="inputForm">
                        <label for="">Subtítulo</label>
                        <input type="text" name="subtitle" class="form-control">
                    </div>
                    <div class="inputForm">
                        <label for="">Cuerpo</label>
                        <textarea name="body" id="" cols="30" rows="10" class="form-control"></textarea>
                    </div>

                    <div class="inputForm">
                        <label for="">Publicado en fecha</label>
                        <input type="date" name="published_at" class="form-control">
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('posts.index') }}" class="buttonTextWt">Volver</a></button>
                        <button type="submit" class="stylingButtons green buttonTextWt">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection