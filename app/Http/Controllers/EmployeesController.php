<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\DestroyAllEmployees;
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
        $employees = Db::Table('users')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->select('users.name', 'users.nif', 'employees.dni', 'employees.id')
            ->get()
            ->toArray();

        return view('employee.index', compact('employees'));
    }

    /**
     * Show form for creating employee
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created employee
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validated();

        $employee = new Employee();
        $userId = Db::Table('users')->where('nif', '=', $request->input('nif'))->value('users.id')->get();
        $employee->user_id = $userId;
        $employee->dni = $request->input('dni');
        $employee->save();

        return redirect()->route('employees.index')->withSuccess(__('Empleado creado correctamente.'));
    }

    public function show(Employee $employee)
    {
        return view('employees.show', [
            'employee' => $employee
        ]);
    }

    /**
     * Edit user data
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $employeeFix = Db::Table('users')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->where('employees.id', '=', $employee->id)
            ->select('users.name', 'users.nif', 'employees.dni', 'employees.id')
            ->get()
            ->toArray();

        return view('employees.edit', compact('employeeFix'));
    }

    /**
     * Update user data
     *
     * @param Employee $employee
     * @param UpdateUserRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Employee $employee, Request $request)
    {
        $this->validate($request, [
            'dni' => 'required|size:9,unique:employees,dni',
            'nif' => 'required|size:9,unique:users,nif',
        ]);

        $employee = Employee::find($employee->id);
        $userId = Db::Table('users')->where('nif', '=', $request->input('nif'))->value('users.id')->get();
        $employee->user_id = $userId;
        $employee->dni = $request->input('dni');

        return redirect()->route('employees.index')->withSuccess(__('Empleado modificado correctamente.'));
    }

    /**
     * Delete employee data
     *
     * @param Employee $employee
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->withSuccess(__('Empleado eliminado correctamente.'));
    }

    public function deleteAll()
    {
        DestroyAllEmployees::dispatch();

        return redirect()->route('employees.index')
            ->withSuccess(__('Estamos eliminando a TODOS los empleados, en breve terminamos;)'));
    }
}
