@extends('layouts.app-master')

@section('content')
<div class="noticiasIntranet">
    <div class="innerNoticiasIntranet paddingFix">
        <div class="top">
            <h1>Noticias</h1>
            <h3 class="">Ponte al día con las noticias que te interesan</h3>
        </div>
        <div class="bottom">
            <div class="createNoticia">
                <button class="stylingButtons green"><a href="{{ route('posts.create') }}" class="buttonTextWt">Crear noticia</a></button>
                <br>
            </div>
            <table class="">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Título</th>
                        <th>Fecha de publicación</th>
                        <th>Fecha de creación</th>
                        <th colspan="2">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                    <tr>
                        <td class="">{{ $post->id }}</td>
                        <td class="">{{ $post->title }}</td>
                        <td class="">{{ date('Y-m-d', strtotime($post->published_at)) }}</td>
                        <td class="">{{ date('Y-m-d', strtotime($post->created_at)) }}</td>
                        <td class="">
                            <button class="stylingButtons blue"><a href="posts/{{ $post->id }}" class="buttonTextWt">Mostrar</a></button>
                            <button class="stylingButtons green"><a href="posts/{{ $post->id }}/edit" class="buttonTextWt">Editar</a></button>
                            <form action="posts/{{ $post->id }}" method="post">
                                {{ csrf_field() }}
                                @method('DELETE')
                                <button class="stylingButtons red buttonTextWt" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a href="{{ route('intranet.index') }}" class="buttonTextWt">Volver</a></button>
        </div>
    </div>
</div>
@endsection