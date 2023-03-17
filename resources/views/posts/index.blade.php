@extends('layouts.app-master')

@section('content')
<div class="">
    <h1>Noticias</h1>
    <div class="">
        Ponte al día con las noticias que te interesan
    </div>
    <div class="">
        <div class="">
            @role('asesor')
            <div class="">
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Crear noticia</a>
                <br>
            </div>
            @endrole
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
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ date('Y-m-d', strtotime($post->published_at)) }}</td>
                        <td>{{ date('Y-m-d', strtotime($post->created_at)) }}</td>
                        <td class="">
                            <a href="posts/{{ $post->id }}" class="">Mostrar</a>
                            @role('asesor')
                            <a href="posts/{{ $post->id }}/edit" class="">Editar</a>
                            <form action="posts/{{ $post->id }}" method="post" class="">
                                {{ csrf_field() }}
                                @method('DELETE')
                                <button class="" type="submit">Eliminar</button>
                            </form>
                            @endrole
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="">
    <button class=""><a href="{{ route('home.index') }}" class="">Volver</a></button>
</div>
@endsection