@extends('layouts.app-master')

@section('content')
<section class="createUsers">
    <div class="innerCreateUsers">
        <div class="top">
            <h1>Añadir nueva empresa</h1>
            <h3 class="">Añade un nuevo usuario o empresa.</h3>
        </div>
        <div class="bottom">
            <div class="innerBottom">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="inputDiv">
                        <label for="name" class="form-label">Nombre</label>
                        <input value="{{ old('name') }}" type="text" class="form-control" name="name" placeholder="Nombre" required>

                        @if ($errors->has('name'))
                        <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="inputDiv">
                        <label for="email" class="form-label">Email</label>
                        <input value="{{ old('email') }}" type="email" class="form-control" name="email" placeholder="Email" required>
                        @if ($errors->has('email'))
                        <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="inputDiv">
                        <select name="companyIdSlc" id="" class="">
                            <option value="nif" class="">NIF</option>
                            <option value="nie" class="">NIE</option>
                            <option value="cif" class="">CIF</option>
                        </select>
                    </div>
                    <div class="inputDiv">
                        <label for="companyId" class="">ID empresa</label>
                        <input value="{{ old('nif') }}" type="text" class="" name="companyId" placeholder="ID Empresa" required>
                        @if ($errors->has('companyId'))
                        <span class="">{{ $errors->first('companyId') }}</span>
                        @endif
                    </div>
                    <div class="buttonsNav">
                        <button class="stylingButtons blue"><a href="{{ route('users.index') }}" class="buttonTextWt">Volver</a></button>
                        <button type="submit" class="stylingButtons green buttonTextWt">Guardar usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection