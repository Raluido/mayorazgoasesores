@extends('layouts.app-master')

@section('content')
<section class="editRoles">
    <div class="innerEditRoles">
        <div class="top">
            <h1 class="">Asignar permisos</h1>
            <h3 class="">Editar roles y manejar permisos</h3>
        </div>
        <div class="bottom">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong>Hubo algún problema con el input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('roles.update', $role->id) }}">
                @method('patch')
                @csrf
                <div class="">
                    <label for="name" class="" style="margin-right:2em;">Nombre</label>
                    <input value="{{ $role->name }}" type="text" class="" name="name" placeholder="Name" required>
                </div>

                <table class="t">
                    <thead>
                        <th scope="col" width="1%"><input type="checkbox" name="all_permission"></th>
                        <th scope="col" width="20%">Name</th>
                        <th scope="col" width="1%">Guard</th>
                    </thead>

                    @foreach ($permissions as $permission)
                    <tr>
                        <td>
                            <input type="checkbox" name="permission[{{ $permission->name }}]" value="{{ $permission->name }}" class='permission' {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                        </td>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->guard_name }}</td>
                    </tr>
                    @endforeach
                </table>
                <div class="buttonsNav">
                    <button class="stylingButtons blue"><a href="{{ route('roles.index') }}" class="buttonTextWt">Volver</a></button>
                    <button type="submit" class="stylingButtons green buttonTextWt">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
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
</section>