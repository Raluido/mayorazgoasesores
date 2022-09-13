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


class EmployeesController extends Controller
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
            ->paginate(10);

        return view('employees.index', compact('employees'));
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
        $userId = Db::Table('users')->where('nif', '=', $request->input('nif'))->select('id')->exists();

        if ($userId) {
            $employee = new Employee();
            $userId = Db::Table('users')->where('nif', '=', $request->input('nif'))->value('users.id')->get();
            $employee->user_id = $userId;
            $employee->dni = $request->input('dni');
            $employee->save($request->validated());

            return redirect()->route('employees.index')->withSuccess(__('Empleado creado correctamente.'));
        } else {
            return redirect()->route('employees.index')->withErrors(__('No existe una empresa asociada a ese nif en nuestra base de datos, para crear un empleado, primero tendrá que crearla.'));
        }
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

    public function destroy($id)
    {
        $check = Db::Table('payrolls')->where('employee_id', '=', $id)->count();
        $payrolls = Db::Table('payrolls')->where('employee_id', '=', $id)->value('filename');

        if ($check <= 1) {
            unlink($payrolls);
        } else {
            foreach ($payrolls as $index) {
                unlink($index);
            }
        }
        Db::Table('payrolls')->where('employee_id', '=', $id)->delete();

        Db::Table('employees')->where('id', '=', $id)->delete();

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
