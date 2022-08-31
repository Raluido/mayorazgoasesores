@extends('layouts.app-master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                @role('asesor')
                    <a href="/posts/create" class="btn btn-primary mb-2">Crear noticia</a>
                    <br>
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
                                <td>{{ dat('Y-m-d', strtotime($post->created_at)) }}</td>
                                @role('asesor')
                                    <td>
                                        <a href="posts/{{ $post->id }}" class="btn btn-primary">Mostrar</a>
                                        <a href="posts/{{ $post->id }}/edit" class="btn btn-primary">Editar</a>
                                        <form action="posts/{{ $post->id }}" method="post" class="d-inline">
                                            {{ csrf_field() }}
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit">Eliminar</button>
                                        </form>
                                    </td>
                                @endrole
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
