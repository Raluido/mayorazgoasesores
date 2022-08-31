@extends('layouts.app-master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('View Post') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h2>{{ $post->title }}</h2>

                        <p>Publicado en: {{ date('Y-m-d', strtotime($post->published_at)) }}</p>
                        <br>
                        <div>
                            {{ $post->body }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
