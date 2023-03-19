@extends('layouts.app-master')

@section('content')
<div class="">
    <h1>Noticias</h1>
    <div class="">
        Ponte al día con las noticias que te interesan
    </div>
    <div class="">
        <button class="create"><a href="{{ route('posts.create') }}" class="">Crear noticia</a></button>
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
                    <button class="show"><a href="posts/{{ $post->id }}">Mostrar</a></button>
                    <button class="edit"><a href="posts/{{ $post->id }}/edit">Editar</a></button>
                    <form action="posts/{{ $post->id }}" method="post">
                        {{ csrf_field() }}
                        @method('DELETE')
                        <button class="delete" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="bottomNav">
        <button class=""><a href="{{ route('home.index') }}" class="">Volver</a></button>
    </div>
</div>
@endsection