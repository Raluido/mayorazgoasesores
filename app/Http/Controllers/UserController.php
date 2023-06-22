<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserPassword;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function editPassword()
    {
        $user = auth()->user();

        return view('user.editPassword')
            ->with('user', $user);
    }

    /**
     * Update user password
     *
     * @param UpdateUserPassword $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(UpdateUserPassword $request)
    {
        $user = User::find(auth()->user()->id);

        $user->password = $request->input('password');
        $user->update($request->validated());

        return redirect()
            ->route('user.editPassword', ['user' => $user])
            ->withSuccess(__('La constraseña se actualizado correctamente'));
    }

    public function editData()
    {
        $user = auth()->user();

        return view('user.editData', ['user' => $user]);
    }

    public function updateData(User $user, UpdateUserRequest $request)
    {
        $user = User::find(auth()->user()->id);

        $user->update($request->validated());

        return redirect()
            ->route('user.editData', ['user' => $user])
            ->withSuccess(__('Se ha editado con éxito'));
    }
}
