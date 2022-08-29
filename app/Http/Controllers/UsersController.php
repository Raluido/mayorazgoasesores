<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\AddUsersAuto;
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

        Mail::to("raluido@gmail.com")->send(new ContactMail($data));

        $user->save();

        // $user->syncRoles($request->get('role'));
        $user->assignRole($request->input('role'));

        return redirect()->route('users.index')->withSuccess(__('User created successfully.'));
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
        $user->delete();

        return redirect()->route('users.index')
            ->withSuccess(__('User deleted successfully.'));
    }

    public function deleteAll()
    {
        DB::table('users')->where('id', '>', '3')->delete();

        return view('users.index');
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
}
