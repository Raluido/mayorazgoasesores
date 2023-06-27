<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\EmployeeUser;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Log;
use App\Jobs\DestroyAllEmployees;
use App\Models\Payroll;
use App\Models\UserEmployee;
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
        $employees = Db::Table('employees')
            ->select('users.id as userId', 'users.name', 'users.nif', 'employees.dni', 'employees.id')
            ->join('employee_user', 'employee_user.employee_id', '=', 'employees.id')
            ->join('users', 'employee_user.user_id', '=', 'users.id')
            ->orderBy('employees.id')
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
        $nif = $request->input('companyId');
        $dni = $request->input('employeeId');
        $employeeId = Employee::where('dni', $dni)->value('id');

        $userId = Db::Table('users')
            ->where('nif', '=', $nif)
            ->value('id');

        if ($userId == "") {
            return redirect()
                ->route('employees.index')
                ->withErrors(__('No existe una empresa asociada a ese nif en nuestra base de datos, para crear un empleado, primero tendrá que crearla.'));
        }

        $employee = Db::Table('users')
            ->join('employee_user', 'employee_user.user_id', '=', 'users.id')
            ->join('employees', 'employee_user.employee_id', '=', 'employees.id')
            ->where('users.nif', '=', $nif)
            ->where('employees.dni', '=', $dni)
            ->get();

        if (count($employee) > 0) {
            return redirect()
                ->route('employees.index')
                ->withErrors(__('Un empleado con esos datos ya figura en la empresa.'));
        } else {
            if ($employeeId == "") {
                $employee = new Employee();
                $employee->dni = $dni;
                $employee->save($request->validated());
            }

            $employeeId = Employee::where('dni', $dni)->value('id');

            if ($employeeId != "") {
                $user = User::find($userId);
                $user->employees()->attach($employeeId);
            }

            $created = Db::Table('users')
                ->join('employee_user', 'employee_user.user_id', '=', 'users.id')
                ->join('employees', 'employee_user.employee_id', '=', 'employees.id')
                ->where('users.nif', '=', $nif)
                ->where('employees.dni', '=', $dni)
                ->get();

            if (count($created) > 0) {
                return redirect()
                    ->route('employees.index')
                    ->withSuccess(__('Empleado creado correctamente.'));
            } else {
                return redirect()
                    ->route('employees.index')
                    ->withErrors(__('Ha habido un error al crear al empleado.'));
            }
        }
    }

    public function show(Employee $employee)
    {
        $employee = Db::Table('users')
            ->join('employee_user', 'employee_user.user_id', '=', 'users.id')
            ->join('employees', 'employee_user.employee_id', '=', 'employees.id')
            ->where('employees.id', '=', $employee->id)
            ->get();

        return view('employees.show', ['employee' => $employee]);
    }

    /**
     * Edit user data
     *
     * @param Employee $employee
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $employee = Employee::find($employee->id);

        return view('employees.edit', ['employee' => $employee]);
    }

    /**
     * Update user data
     *
     * @param Employee $employee
     * @param UpdateUserRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Employee $employee, UpdateEmployeeRequest $request)
    {
        $employee = Employee::find($employee->id);
        $employee->dni = $request->employeeId;
        $save = $employee->save();

        if ($save) {
            return redirect()
                ->route('employees.index')
                ->withSuccess(__('Empleado modificado correctamente.'));
        }
    }

    public function destroy(Employee $employee, User $user)
    {
        $payrolls = Db::Table('payrolls')
            ->select('payrolls.id', 'payrolls.filename')
            ->join('employee_user', 'payrolls.employee_user_id', '=', 'employee_user.id')
            ->join('employees', 'employee_user.employee_id', '=', 'employees.id')
            ->join('users', 'employee_user.user_id', '=', 'users.id')
            ->where('employees.id', $employee->id)
            ->where('users.id', $user->id)
            ->get();


        if (count($payrolls) > 0) {
            foreach ($payrolls as $payroll) {
                if (Storage::exists($payroll->filename)) {
                    $delete = Storage::delete($payroll->filename);
                    if ($delete) {
                        $delete = Payroll::where('id', $payroll->id)->delete();
                        if (!$delete) {
                            return redirect()->route('employees.index')
                                ->withErrors(__('Ha habido un error al intentar eliminar las nóminas del empleado, intentelo de nuevo.'));
                        }
                    } else {
                        return redirect()->route('employees.index')
                            ->withErrors(__('Ha habido un error al intentar eliminar las nóminas del empleado, intentelo de nuevo.'));
                    }
                } else {
                    return redirect()->route('employees.index')
                        ->withErrors(__('Ha habido un error al intentar eliminar las nóminas del empleado, intentelo de nuevo.'));
                }
            }
        }

        $user = User::find($user->id);
        $user->employees()->detach($employee->id);

        // delete the employee register if it isnot register in other company

        $employeeCnt = EmployeeUser::where('employee_id', $employee->id)->count();
        if ($employeeCnt == 0) {
            $delete = Employee::find($employee->id)->delete();
            if ($delete) {
                return redirect()->route('employees.index')
                    ->withSuccess(__('Empleado eliminado correctamente.'));
            } else {
                return redirect()->route('employees.index')
                    ->withErrors(__('Ha habido un error al intentar eliminar las nóminas del empleado, intentelo de nuevo.'));
            }
        }
        return redirect()->route('employees.index')
            ->withSuccess(__('Empleado eliminado correctamente, recuerda que la ficha del empleado con dni ' . $employee->dni . ' persistira porque aun forma parte de otra empresa.'));
    }

    public function deleteAll()
    {
        DestroyAllEmployees::dispatch();

        return redirect()->route('employees.index')
            ->withSuccess(__('Estamos eliminando a TODOS los empleados, en breve terminamos;)'));
    }
}
