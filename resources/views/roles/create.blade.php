@extends('layouts.app-master')

@section('content')
<section class="createRoles">
    <div class="createInnerRoles">
        <div class="top">
            <div class="">
                <h1>Añadir nuevo rol</h1>
            </div>
            <div class="">
                Gestionar roles y añadir permisos
            </div>
        </div>
        <div class="bottom">
            @if (count($errors) > 0)
            <div class="">
                <strong>Whoops!</strong> Hubo algún problema con la entrada.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div class="">
                    <label for="name" class="" style="margin-right:2em;">Nombre</label>
                    <input value="{{ old('name') }}" type="text" class="" name="name" placeholder="Name" required>
                </div>

                <!-- <label for="permissions" class="">Asignar permisos</label> -->

                <table class="">
                    <thead>
                        <th scope="col" width="0.2%"><input type="checkbox" name="all_permission"></th>
                        <th scope="col" width="10%">Nombre</th>
                        <th scope="col" width="1%">Guard</th>
                    </thead>

                    @foreach ($permissions as $permission)
                    <tr>
                        <td>
                            <input type="checkbox" name="permission[{{ $permission->name }}]" value="{{ $permission->name }}" class='permission'>
                        </td>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->guard_name }}</td>
                    </tr>
                    @endforeach
                </table>
            </form>
        </div>
        <div class="buttonsNav">
            <button class="stylingButtons blue"><a href="{{ route('users.index') }}" class="buttonTextWt">Volver</a></button>
            <button type="submit" class="stylingButtons green buttonTextWt">Guardar cambios</button>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('[name="all_permission"]').on('click', function() {

            if ($(this).is(':checked')) {
                $.each($('.permission'), function() {
                    $(this).prop('checked', true);
                });
            } else {
                $.each($('.permission'), function() {
                    $(this).prop('checked', false);
                });
            }

        });
    });
</script>
@endsection