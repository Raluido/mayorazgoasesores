@extends('layouts.app-master')

@section('content')
<section class="addPermissions">
    <div class="innerAddPermissions">
        <div class="top">
            <h1>Permisos</h1>
            <h3 class="">Añadir nuevo permiso</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form method="POST" action="{{ route('permissions.store') }}">
                    @csrf
                    <div class="inputForm">
                        <label for="name" class="form-label">Name</label>
                        <input value="{{ old('name') }}" type="text" class="form-control" name="name" placeholder="Name" required>
                        @if ($errors->has('name'))
                        <span class="">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('permissions.index') }}" class="buttonTextWt">Volver</a></button>
                        <button type="submit" class="stylingButtons green buttonTextWt">Guardar permiso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection