<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreEmployeeRequest;
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

        if ($employees[0] == null) {
            echo '<div class="alert alert-warning"><strong>Warning!</strong> No hay empleados en nuestra base de datos aún, se añadiran automaticamente cuando cargues alguna nómina ;) </div>';
        }

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
     * @param Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {

        $userId = Db::Table('users')
            ->where('nif', '=', $request->input('nif'))
            ->value('id');


        if ($userId != null) {
            $employee = new Employee();
            $employee->user_id = $userId;
            $employee->dni = $request->input('dni');
            $employee->save($request->validated());


            return redirect()->route('employees.index')->withSuccess(__('Empleado creado correctamente.'));
        } else {
            return redirect()->route('employees.index')->withErrors(__('No existe una empresa asociada a ese nif en nuestra base de datos, para crear un empleado, primero tendrá que crearla.'));
        }
    }

    public function show($id)
    {
        $employeeData = Db::Table('users')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->where('employees.id', '=', $id)
            ->select('users.name', 'users.nif', 'employees.dni', 'employees.id')
            ->get()
            ->toArray();

        return view('employees.show', compact('employeeData'));
    }

    /**
     * Edit user data
     *
     * @param Employee $employee
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employeeFix = Db::Table('users')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->where('employees.id', '=', $id)
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
    public function update($id, Request $request)
    {
        $employee = Employee::find($id);
        $userId = Db::Table('users')->where('nif', '=', $request->input('nif'))->value('users.id')->get();
        $employee->user_id = $userId;
        $employee->dni = $request->input('dni');

        return redirect()->route('employees.index')->withSuccess(__('Empleado modificado correctamente.'));
    }

    public function destroy($id)
    {
        $payrollsId = DB::Table('employees')
            ->join('payrolls', 'payrolls.employee_id', '=', 'employees.id')
            ->where('employee_id', '=', $id)
            ->select('payrolls.filename')
            ->get();

        if ($payrollsId != array()) {
            foreach ($payrollsId as $index) {
                $payroll = Db::Table('payrolls')
                    ->where('filename', '=', (array_values((array)$index))[0])
                    ->delete();

                if ($payroll) {
                    try {
                        unlink((array_values((array)$index))[0]);
                    } catch (\Throwable $th) {
                        continue;
                    }
                }
            }
        }

        $delete = DB::Table('employees')
            ->where('id', '=', $id)
            ->delete();

        if ($delete) {
            return redirect()->route('employees.index')
                ->withSuccess(__('Empleado eliminado correctamente.'));
        } else {
            return redirect()->route('employees.index')
                ->withErrors(__('Ha habido un error al intentar eliminar el empleado.'));
        }
    }

    public function deleteAll()
    {
        DestroyAllEmployees::dispatch();

        return redirect()->route('employees.index')
            ->withSuccess(__('Estamos eliminando a TODOS los empleados, en breve terminamos;)'));
    }
}
