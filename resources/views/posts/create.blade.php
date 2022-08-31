@extends('layouts.app-master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Crear noticia') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="/post/store" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="">Título</label>
                                <input type="text" name="title" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">Cuerpo</label>
                                <textarea name="body" id="" cols="30" rows="10" class="form-control"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="">Publicado en fecha</label>
                                <input type="date" name="published_at" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
