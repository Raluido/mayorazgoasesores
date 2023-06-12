<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserPassword;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Jobs\DestroyAll;
use App\Mail\AddUserNotification;
use DB;
use Illuminate\Support\Facades\File;


class UsersController extends Controller
{
    /**
     * Display all users
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show form for creating user
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        return view('users.create');
    }

    /**
     * Store a newly created user
     *
     * @param User $user
     * @param StoreUserRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, StoreUserRequest $request)
    {
        $user->name = $request->input('name');
        $user->nif = $request->input('nif');
        $user->email = $request->input('email');
        $password = Str::random(10);
        $user->password = $password;

        $data = array(
            'nif'      =>  $user->nif,
            'password'   =>   $password
        );

        $user->save($request->validated());
        $result = $user->assignRole('user');

        if ($result) {

            Mail::to($user->email)->send(new AddUserNotification($data));
            return redirect()
                ->route('users.index')
                ->withSuccess(__('Empresa creada correctamente.'));
        } else {
            return redirect()
                ->route('users.index')
                ->withErrors(__('Ha habido un error al intentar crear la empresa.'));
        }
    }

    /**
     * Show user data
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * Edit user data
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update user data
     *
     * @param User $user
     * @param UpdateUserRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $user->update($request->validated());

        return redirect()
            ->route('users.index')
            ->withSuccess(__('User updated successfully.'));
    }

    /**
     * Delete user data
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $payrollsId = DB::Table('users')
            ->select('payrolls.filename', 'payrolls.id')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->join('payrolls', 'payrolls.employee_id', '=', 'employees.id')
            ->where('employees.user_id', '=', $user->id)
            ->get();

        if (isset($payrollsId[0])) {
            foreach ($payrollsId as $index) {
                if (File::exists((array_values((array)$index))[0])) {
                    unlink((array_values((array)$index))[0]);

                    $delete = Db::Table('payrolls')
                        ->where('id', '=', (array_values((array)$index))[1])
                        ->delete();
                }

                if (isset($delete) && !$delete) {
                    return redirect()
                        ->route('users.index')
                        ->withErrors('Hemos registrado un error al eliminar la nómina con id ' . array_values((array)$index)[1]);
                }
            }
        }


        $delete = DB::Table('employees')
            ->where('user_id', '=', $user->id)
            ->delete();

        if (!$delete) {
            return redirect()
                ->route('users.index')
                ->withErrors('Hemos registrado un error al eliminar al empleado con id ' . $user->id);
        }

        $costsimputsId = DB::Table('costs_imputs')
            ->where('user_id', '=', $user->id)
            ->select('filename')
            ->get();

        if (isset($costsimputsId[0])) {
            foreach ($costsimputsId as $index) {
                if (File::exists(array_values((array)$index))[0]) {
                    unlink((array_values((array)$index))[0]);
                    $delete = Db::Table('costs_imputs')
                        ->where('filename', '=', (array_values((array)$index))[0])
                        ->delete();
                }
                if (isset($delete) && !$delete) {
                    return redirect()
                        ->route('users.index')
                        ->withErrors('Hemos registrado un error al eliminar el documento de imputación de costes ' . array_values((array)$index)[0]);
                }
            }
        }

        $othersdocumentsId = DB::Table('others_documents')
            ->where('user_id', '=', $user->id)
            ->select('filename')
            ->get();

        if (isset($othersdocumentsId[0])) {
            foreach ($othersdocumentsId as $index) {
                if (File::exists((array_values((array)$index))[0])) {
                    unlink((array_values((array)$index))[0]);
                    $delete = Db::Table('others_documents')
                        ->where('filename', '=', (array_values((array)$index))[0])
                        ->delete();
                }
                if (isset($delete) && !$delete) {
                    return redirect()
                        ->route('users.index')
                        ->withErrors('Hemos registrado un error al eliminar el documento de otros documentos ' . array_values((array)$index)[0]);
                }
            }
        }

        $delete = $user->delete();

        if (!$delete) {
            return redirect()
                ->route('users.index')
                ->withErrors('Hemos registrado un error al eliminar la empresa.');
        } else {
            return redirect()->route('users.index')
                ->withSuccess(__('Empresas eliminadas correctamente.'));
        }
    }

    public function deleteAll()
    {
        DestroyAll::dispatch();

        return redirect()
            ->route('users.index')
            ->withSuccess(__('Estamos eliminando TODOS los datos, en breve terminamos;)'));
    }
}
