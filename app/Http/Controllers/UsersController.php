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
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\AddUsersAuto;
use App\Jobs\DestroyAll;
use App\Mail\AddUserNotification;
use DB;


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
        return view('users.create')->with([
            'user' => $user,
            'userRole' => $user->roles->pluck('name')->toArray(), 'roles' => Role::latest()->get()
        ]);
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

        $validated = $request->validated();

        $user = new User();
        $user->name = $request->input('name');
        $user->nif = $request->input('nif');
        $user->email = $request->input('email');
        $password = Str::random(10);
        $user->password = $password;

        $data = array(
            'nif'      =>  $user->nif,
            'password'   =>   $password
        );

        Mail::to("raluido@gmail.com")->send(new AddUserNotification($data));

        $user->save();
        $user->assignRole($request->input('role'));

        return redirect()->route('users.index')->withSuccess(__('Empresa creada correctamente.'));
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
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'userRole' => $user->roles->pluck('name')->toArray(),
            'roles' => Role::latest()->get()
        ]);
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

        $user->syncRoles($request->get('role'));

        return redirect()->route('users.index')
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
        $check = DB::Table('users')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->join('payrolls', 'payrolls.employee_id', '=', 'employees.id')
            ->where('user_id', '=', $user->id)
            ->select('payrolls.id')
            ->exists();

        $check1 = Db::Table('employees')->where('user_id', '=', $user->id)->exists();
        $check2 = Db::Table('costs_imputs')->where('user_id', '=', $user->id)->exists();
        $check3 = Db::Table('others_documents')->where('user_id', '=', $user->id)->exists();

        if ($check) {
            $payrollFilename = DB::Table('users')
                ->join('employees', 'employees.user_id', '=', 'users.id')
                ->join('payrolls', 'payrolls.employee_id', '=', 'employees.id')
                ->where('user_id', '=', $user->id)
                ->value('payrolls.filename');
            foreach ($payrollFilename as $index) {
                unlink($index);
            }
            $payrollsId = DB::Table('users')
                ->join('employees', 'employees.user_id', '=', 'users.id')
                ->join('payrolls', 'payrolls.employee_id', '=', 'employees.id')
                ->where('user_id', '=', $user->id)
                ->value('payrolls.id');

            Db::Table('payrolls')->whereIn('id', '=', $payrollsId)->delete();


            $check = Db::Table('employees')->where('user_id', '=', $user->id)->exists();

            if ($check) {
                Db::Table('employees')->where('user_id', '=', $user->id)->delete();
            }

            $check = Db::Table('costs_imputs')->where('user_id', '=', $user->id)->exists();

            if ($check) {
                $costsimputsId = Db::Table('costs_imputs')->where('user_id', '=', $user->id)->value('filename');
                foreach ($costsimputsId as $index) {
                    unlink($index);
                }
                Db::Table('costs_imputs')->where('user_id', '=', $user->id)->delete();
            }

            $check = Db::Table('others_documents')->where('user_id', '=', $user->id)->exists();

            if ($check) {
                $othersdocumentsId = Db::Table('others_documents')->where('user_id', '=', $user->id)->value('filename');
                foreach ($othersdocumentsId as $index) {
                    unlink($index);
                }
                Db::Table('others_documents')->where('user_id', '=', $user->id)->delete();
            }

            $user->delete();
        } elseif ($check1 || $check2 || $check3) {

            if ($check1) {
                Db::Table('employees')->where('user_id', '=', $user->id)->delete();
            }


            if ($check2) {
                $costsimputsId = Db::Table('costs_imputs')->where('user_id', '=', $user->id)->value('filename');
                foreach ($costsimputsId as $index) {
                    unlink($index);
                }
                Db::Table('costs_imputs')->where('user_id', '=', $user->id)->delete();
            }


            if ($check3) {
                $othersdocumentsId = Db::Table('others_documents')->where('user_id', '=', $user->id)->value('filename');
                foreach ($othersdocumentsId as $index) {
                    unlink($index);
                }
                Db::Table('others_documents')->where('user_id', '=', $user->id)->delete();
            }

            $user->delete();
        } else {
            $user->delete();
        }

        return redirect()->route('users.index')
            ->withSuccess(__('Empresas eliminado correctamente.'));
    }

    public function deleteAll()
    {
        DestroyAll::dispatch();

        return redirect()->route('users.index')
            ->withSuccess(__('Estamos eliminando TODOS los datos, en breve terminamos;)'));
    }

    public function AddUsersAutoForm()
    {
        return view('users.addUsersAutoForm');
    }

    public function AddUsersAuto(Request $request)
    {
        $file = $request->file('payrolls');

        if ($request->hasFile('payrolls')) {
            $allowedfileExtension = ['pdf'];
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);
            if ($check) {
                $filenamewithextension = "addCompanies.pdf";
                $file->storeAs('storage/media/',  $filenamewithextension);
                AddUsersAuto::dispatch($filenamewithextension);
            } else {
                echo '<div class="alert alert-warning"><strong>Warning!</strong> Sólo se admiten archivos con extensión .pdf</div>';

                return view('users.addUsersAutoForm');
            }
        } else {
            echo '<div class="alert alert-warning"><strong>Warning!</strong> No has añadido ningun archivo aún.</div>';

            return view('users.addUsersAutoForm');
        }

        return view('users.addUsersAutoForm')->with('successMsg', "Estamos añadiendo las empresas, tardaremos unos minutos, gracias ;)");
    }

    public function editPassword()
    {
        $user = auth()->user();

        return view('user.editPassword')->with('user', $user);
    }

    public function updatePassword(Request $request, UpdateUserPassword $updateRequest)
    {
        $user = User::find(auth()->user()->id);

        $user->password = $request->input('password');
        $user->update($updateRequest->validated());

        return redirect()->route('user.editPassword')->with('user', $user)->withSuccess(__('Se ha editado con éxito'));
    }

    public function editData()
    {
        $user = auth()->user();
        return view('user.editData')->with('user', $user);
    }

    public function updateData(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->update();

        return redirect()->route('user.editData')->with('user', $user)->withSuccess(__('Se ha editado con éxito'));
    }
}
