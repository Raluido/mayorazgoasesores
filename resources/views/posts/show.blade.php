@extends('layouts.app-master')

@section('content')
<div class="mt-4">
    <h1>Noticias</h1>
    <div class="lead mb-3">
        Las noticias de actualidad
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="card-header">
                        {{ $post->title }}
                    </div>
                    <div class="">
                        {{ $post->body }}
                    </div>
                    <div class="mt-4">
                        <p>Publicado en: {{ date('Y-m-d', strtotime($post->published_at)) }}</p>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <a class="btn btn-primary" href="{{ route('home.index') }} ">Volver</a>
            </div>
        </div>
    </div>
</div>
@endsection