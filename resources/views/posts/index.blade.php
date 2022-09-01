@extends('layouts.app-master')

@section('content')
    <div class="mt-5">
        <h1>Noticias</h1>
        <div class="lead mb-3">
            Ponte al día con las noticias que te interesan
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                @role('asesor')
                    <div class="d-flex justify-content-end mb-4">
                        <a href="{{ route('posts.create') }}" class="btn btn-primary">Crear noticia</a>
                        <br>
                    </div>
                @endrole
                <table class="table table-bordered">
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
                                <td class="d-flex justify-content-around">
                                    <a href="posts/{{ $post->id }}" class="btn btn-primary">Mostrar</a>
                                    @role('asesor')
                                        <a href="posts/{{ $post->id }}/edit" class="btn btn-primary">Editar</a>
                                        <form action="posts/{{ $post->id }}" method="post" class="d-inline">
                                            {{ csrf_field() }}
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit">Eliminar</button>
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
    <div class="d-flex justify-content-center mt-5">
        <button class="btn btn-secondary"><a href="{{ route('home.index') }}"
                class="text-decoration-none text-white">Volver</a></button>
    </div>
@endsection
