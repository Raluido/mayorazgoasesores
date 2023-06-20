<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Log;
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
            ->select('users.name', 'users.nif', 'employees.dni', 'employees.id')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->paginate(10);

        if (count($employees) > 0) {
            return view('employees.index', compact('employees'));
        } else {
            return view('employees.index', compact('employees'))
                ->with('msj', 'No hay empleados en nuestra base de datos aún, se añadiran automaticamente cuando cargues alguna nómina.');
        }
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
            ->where('nif', '=', $request->input('companyId'))
            ->value('id');

        $employeeId = Db::Table('users')
            ->join('employees', 'users.id', '=', 'employees.user_id')
            ->where('users.nif', '=', $request->input('companyId'))
            ->where('employees.dni', '=', $request->input('employeeId'))
            ->value('id');

        if ($userId != null && $employeeId == null) {
            $employee = new Employee();
            $employee->user_id = $userId;
            $employee->dni = $request->input('employeeId');
            $employee->save($request->validated());

            return redirect()
                ->route('employees.index')
                ->withSuccess(__('Empleado creado correctamente.'));
        } else if ($userId == null) {
            return redirect()
                ->route('employees.index')
                ->withErrors(__('No existe una empresa asociada a ese nif en nuestra base de datos, para crear un empleado, primero tendrá que crearla.'));
        } else if ($employeeId != null) {
            return redirect()
                ->route('employees.index')
                ->withErrors(__('Un empleado con esos datos ya figura en la empresa.'));
        }
    }

    public function show($id)
    {
        $employee = Db::Table('users')
            ->select('users.name', 'users.nif', 'employees.dni', 'employees.id')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->where('employees.id', '=', $id)
            ->get();

        return view('employees.show', ['employee' => $employee]);
    }

    /**
     * Edit user data
     *
     * @param Employee $employee
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $employee = Db::Table('users')
            ->select('users.name', 'users.nif', 'employees.dni', 'employees.id')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->where('employees.id', '=', $id)
            ->get();

        return view('employees.edit', compact('employee'));
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

        return redirect()
            ->route('employees.index')
            ->withSuccess(__('Empleado modificado correctamente.'));
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
                        unlink(public_path((array_values((array)$index))[0]));
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
