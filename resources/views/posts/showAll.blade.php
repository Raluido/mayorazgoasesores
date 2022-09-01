@extends('layouts.app-master')

@section('content')
    <div class="mt-5">
        <h1>Noticias</h1>
        <div class="lead mb-3">
            Ponte al día con las noticias que te interesan
        </div>
        <div class="container mt-5">
            @foreach ($posts as $post)
                <div class="border-bottom mb-5">
                    <h3>{{ $post->title }}</h3>
                </div>
                <div class="">
                    <p>{{ $post->body }}</p>
                </div>
                <div class="border-top mt-5">
                    <p>Publicado en fecha {{ date('d-m-Y', strtotime($post->published_at)) }}</p>
                </div>
            @endforeach
        </div>
    </div>
    <div class="d-flex justify-content-center mt-5">
        <button class="btn btn-secondary"><a href="{{ route('home.index') }}"
                class="text-decoration-none text-white">Volver</a></button>
    </div>
@endsection
