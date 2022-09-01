@extends('layouts.app-master')

@section('content')
    <div class="mt-5">
        <h1>Noticias</h1>
        <div class="lead mb-3">
            Ponte al día con las noticias que te interesan
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
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
                                    <a href="post/{{ $post->id }}" class="btn btn-primary">Mostrar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
