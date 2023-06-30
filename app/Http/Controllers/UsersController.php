<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Jobs\DestroyAll;
use App\Mail\AddUserNotification;
use DB;
use Illuminate\Support\Facades\Storage;


class UsersController extends Controller
{
    /**
     * Display all users
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $users = User::orderBy('id')
            ->paginate(10);

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
        $user->nif = $request->input('companyId');
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
        $update = $user->update($request->validated());

        if ($update) {
            return redirect()
                ->route('users.index')
                ->withSuccess(__('Usuario actualizado correctamente.'));
        } else {
            return redirect()
                ->route('users.index')
                ->withErrors(__('No hemos podido modificar la empresa.'));
        }
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
        $payrolls = DB::Table('users')
            ->select('payrolls.filename', 'payrolls.id')
            ->join('employee_user', 'employee_user.user_id', '=', 'users.id')
            ->join('employees', 'employee_user.employee_id', '=', 'employees.id')
            ->join('payrolls', 'employee_user.id', '=', 'payrolls.employee_user_id')
            ->where('users.id', '=', $user->id)
            ->get();

        if (count($payrolls) > 0) {
            foreach ($payrolls as $index) {
                if (Storage::exists($index->filename)) {
                    $delete = Storage::delete($index->filename);
                    if ($delete) {
                        $delete = Db::Table('payrolls')
                            ->where('id', '=', $index->id)
                            ->delete();
                        if (!$delete) {
                            return redirect()
                                ->route('users.index')
                                ->withErrors('Hemos registrado un error al eliminar la nómina con id ' . $index->filename);
                        }
                    } else {
                        return redirect()
                            ->route('users.index')
                            ->withErrors('Hemos registrado un error al eliminar la nómina con id ' . $index->filename);
                    }
                }
            }
        }

        $employees = DB::Table('users')
            ->join('employee_user', 'users.id', '=', 'employee_user.user_id')
            ->join('employees', 'employees.id', '=', 'employee_user.employee_id')
            ->where('users.id', '=', $user->id)
            ->get();

        if (count($employees) > 0) {
            foreach ($employees as $employee) {
                $user->employees()->detach($employee->id);
                if (Db::Table('employee_user')->where('employee_id', $employee->id)->count() == 0) {
                    $delete = Employee::where('id', $employee->id)->delete();
                    if (!$delete) {
                        $user = User::find($user->id);
                        return redirect()
                            ->route('users.index')
                            ->withErrors('Hemos registrado un error al eliminar a un empleado de la empresa ' . $user->id);
                    }
                }
            }
        }

        $costsImputs = DB::Table('costs_imputs')
            ->select('filename', 'id')
            ->where('user_id', '=', $user->id)
            ->get();

        if (count($costsImputs) > 0) {
            foreach ($costsImputs as $index) {
                if (Storage::exists($index->filename)) {
                    $delete = Storage::delete($index->filename);
                    if ($delete) {
                        $delete = Db::Table('costs_imputs')
                            ->where('user_id', '=', $user->id)
                            ->where('id', '=', $index->id)
                            ->delete();
                        if (!$delete) {
                            return redirect()
                                ->route('users.index')
                                ->withErrors('Hemos registrado un error al eliminar el documento de imputación de costes ' . basename($index->filename));
                        }
                    } else {
                        return redirect()
                            ->route('users.index')
                            ->withErrors('Hemos registrado un error al eliminar el documento de imputación de costes ' . basename($index->filename));
                    }
                }
            }
        }

        $othersDocuments = DB::Table('others_documents')
            ->select('filename', 'id')
            ->where('user_id', '=', $user->id)
            ->get();

        if (count($othersDocuments) > 0) {
            foreach ($othersDocuments as $index) {
                if (Storage::exists($index->filename)) {
                    $delete = Storage::delete($index->filename);
                    if ($delete) {
                        $delete = Db::Table('others_documents')
                            ->where('user_id', '=', $user->id)
                            ->where('id', '=', $index->id)
                            ->delete();
                        if (!$delete) {
                            return redirect()
                                ->route('users.index')
                                ->withErrors('Hemos registrado un error al eliminar el documento de otros documentos ' . $index->filename);
                        }
                    } else {
                        return redirect()
                            ->route('users.index')
                            ->withErrors('Hemos registrado un error al eliminar el documento de otros documentos ' . $index->filename);
                    }
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
                ->withSuccess(__('Hemos eliminado correctamente la empresa seleccionada.'));
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
