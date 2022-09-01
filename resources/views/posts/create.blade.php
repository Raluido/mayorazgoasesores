@extends('layouts.app-master')

@section('content')
    <div class="mt-4">
        <h1>Noticias</h1>
        <div class="lead mb-3">
            Crear una nueva noticia
        </div>
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Crear noticia') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="/posts/store" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Título</label>
                                <input type="text" name="title" class="form-control">
                            </div>

                            <div class="form-group mt-2">
                                <label for="">Cuerpo</label>
                                <textarea name="body" id="" cols="30" rows="10" class="form-control"></textarea>
                            </div>

                            <div class="form-group mt-2">
                                <label for="">Publicado en fecha</label>
                                <input type="date" name="published_at" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
