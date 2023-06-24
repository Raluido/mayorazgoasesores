<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Log;
use App\Jobs\DestroyAllEmployees;
use Illuminate\Support\Facades\Storage;
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

        if (($employees->total()) > 0) {
            $msj = '';
            return view('employees.index', compact('employees', 'msj'));
        } else {
            $msj = 'No hay empleados en nuestra base de datos aún, se añadiran automaticamente cuando cargues alguna nómina o un modelo de imputación de costes.';
            return view('employees.index', compact('employees', 'msj'));
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
            ->get();

        $employeeId = Db::Table('users')
            ->join('employees', 'users.id', '=', 'employees.user_id')
            ->where('users.nif', '=', $request->input('companyId'))
            ->where('employees.dni', '=', $request->input('employeeId'))
            ->get();

        if (count($userId) > 0 && count($employeeId) == 0) {
            $employee = new Employee();
            $employee->user_id = $userId;
            $employee->dni = $request->input('employeeId');
            $employee->save($request->validated());

            return redirect()
                ->route('employees.index')
                ->withSuccess(__('Empleado creado correctamente.'));
        } else if (count($userId) == 0 && count($employeeId) == 0) {
            return redirect()
                ->route('employees.index')
                ->withErrors(__('No existe una empresa asociada a ese nif en nuestra base de datos, para crear un empleado, primero tendrá que crearla.'));
        } else if (count($userId) > 0 && count($employeeId) > 0) {
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
            ->where('employees.id', '=', $employee->id)
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
        $userId = Db::Table('users')
            ->where('nif', '=', $request->input('nif'))
            ->value('users.id')
            ->get();
        $employee->user_id = $userId;
        $employee->dni = $request->input('dni');

        return redirect()
            ->route('employees.index')
            ->withSuccess(__('Empleado modificado correctamente.'));
    }

    public function destroy($id)
    {
        $payrolls = DB::Table('employees')
            ->select('payrolls.filename')
            ->join('payrolls', 'payrolls.employee_id', '=', 'employees.id')
            ->where('employee_id', '=', $id)
            ->get();

        if (count($payrolls) > 0) {
            foreach ($payrolls as $index) {
                $delete = Storage::delete($index->filename);
                if ($delete) {
                    $delete = Db::Table('payrolls')
                        ->where('filename', '=', $index->filename)
                        ->delete();
                    if (!$delete) {
                        return redirect()->route('employees.index')
                            ->withErrors(__('Ha habido un error al intentar eliminar las nóminas del empleado, intentelo de nuevo.'));
                    } else {
                        break;
                    }
                } else {
                    log::info("aqui2");
                    return redirect()->route('employees.index')
                        ->withErrors(__('Ha habido un error al intentar eliminar las nóminas del empleado, intentelo de nuevo.'));
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
