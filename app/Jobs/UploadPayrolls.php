<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use setasign\Fpdi\Fpdi;
use App\Models\Employee;
use App\Models\User;
use App\Models\Payroll;
use App\Models\EmployeeUser;
use DB;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\UploadPayrollsNotification;
use App\Mail\JobErrorNotification;
use Exception;


class UploadPayrolls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filename;
    protected $month;
    protected $year;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename, $month, $year)
    {
        $this->filename = $filename;
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filename = $this->filename;
        $monthInput = $this->month;
        $yearInput = $this->year;
        $uploadError = array();

        // Split each page into a new PDF

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile(public_path('storage/media/' . $filename));
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $file = date('d-m-Y his a', time());

        for ($i = 1; $i <= $pageCount; $i++) {
            $newPdf = new Fpdi();
            $newPdf->addPage();
            $newPdf->setSourceFile(public_path('storage/media/' . $filename));
            $newPdf->useTemplate($newPdf->importPage($i));
            $newFilename = sprintf('%s/%s_%s.%s', public_path('storage/media/payrollsTemp'), $file, $i, $extension);
            $newPdf->output($newFilename, 'F');
        }

        // end

        if (Storage::exists('storage/media/' . $filename)) {
            Storage::delete('storage/media/' . $filename);
        }


        // read and rename each .pdf

        $files = glob(public_path('storage/media/payrollsTemp/*.*'));

        foreach ($files as $index) {
            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile($index);
            $content = $pdf->getText();


            preg_match_all('/MENS\s+[0-9]{2}\s+[A-Z]{3}\s+[0-9]{2}/', $content, $period, PREG_OFFSET_CAPTURE);
            preg_match_all('/[0-9]{8}[A-Z]/', $content, $dni, PREG_OFFSET_CAPTURE);
            preg_match_all('/[A-Z]{1}[0-9]{8}/', $content, $cif, PREG_OFFSET_CAPTURE);
            preg_match_all('/[X-Z]{1}[0-9]{7}[A-Z]{1}/', $content, $nie, PREG_OFFSET_CAPTURE);

            try {
                if (count($cif[0]) == 1) {
                    $NIF = $cif[0][0][0];
                    if (count($dni[0]) == 1) {
                        $DNI = $dni[0][0][0];
                    } else {
                        $DNI = $nie[0][0][0];
                    }
                } elseif (count($dni[0]) == 2) {
                    if ($dni[0][0][1] < $dni[0][1][1]) {
                        $NIF = $dni[0][0][0];
                        $DNI = $dni[0][1][0];
                    } else {
                        $NIF = $dni[0][1][0];
                        $DNI = $dni[0][0][0];
                    }
                } elseif (count($nie[0]) == 2) {
                    if ($nie[0][0][1] < $nie[0][1][1]) {
                        $NIF = $nie[0][0][0];
                        $DNI = $nie[0][1][0];
                    } else {
                        $NIF = $nie[0][1][0];
                        $DNI = $nie[0][0][0];
                    }
                } elseif (count($dni[0]) == 1 && count($nie[0]) == 1 && $dni[0][0][1] < $nie[0][0][1]) {
                    $NIF = $dni[0][0][0];
                    $DNI = $nie[0][0][0];
                } elseif (count($dni[0]) == 1 && count($nie[0]) == 1 && $dni[0][0][1] > $nie[0][0][1]) {
                    $NIF = $nie[0][0][0];
                    $DNI = $dni[0][0][0];
                }

                $month = substr($period[0][0][0], 9, 3);
                $year = substr($period[0][0][0], 13);

                if ($month == $monthInput && $year == $yearInput) {
                    rename(public_path('storage/media/payrollsTemp/' . basename($index)), public_path('storage/media/payrollsTemp/' . $NIF . '_' .  $DNI . '_' . $month . 20 . $year . '.' . $extension));
                } else {
                    $uploadError[] = "Error en las fechas/identificación del modelo de imputación de costes";
                }
            } catch (\Throwable $th) {
                $uploadError[] = "Error en las fechas/identificación de la nómina";
                continue;
            }
        }

        // delete temp files

        $files = glob(public_path('storage/media/payrollsTemp/' . basename($filename, '.' . $extension) . '_*.*'));
        foreach ($files as $file) {
            if (Storage::exists('storage/media/payrollsTemp/' . basename($file))) {
                Storage::delete('storage/media/payrollsTemp/' . basename($file));
            }
        }

        // End

        // move to month and year folder

        $path = 'storage/media/payrolls/' . $yearInput;

        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0775, true);
            $path = 'storage/media/payrolls/' . $yearInput . '/' . $monthInput;
            Storage::makeDirectory($path, 0775, true);
        } else {
            $path = 'storage/media/payrolls/' . $yearInput . '/' . $monthInput;
            if (!Storage::exists($path)) {
                Storage::makeDirectory($path, 0775, true);
            }
        }

        $files = glob(public_path('storage/media/payrollsTemp/*.*'));
        $path = 'storage/media/payrolls/' . $yearInput . '/' . $monthInput;

        foreach ($files as $file) {
            $filename = basename($file);
            $nif = substr($filename, 0, 9);
            $dni = substr($filename, 10, 9);
            $userId = User::where('nif', '=', $nif)->value('id');
            $employeeId = Employee::where('dni', $dni)->value('id');

            $employee = Db::Table('users')
                ->select('employees.id')
                ->join('employee_user', 'employee_user.user_id', '=', 'users.id')
                ->join('employees', 'employee_user.employee_id', '=', 'employees.id')
                ->where('users.nif', '=', $nif)
                ->where('employees.dni', '=', $dni)
                ->get();

            if (count($employee) > 0) {
                if ($monthInput . $yearInput == substr($filename, 20, 7)) {

                    // delete if payroll it is already created

                    $delete = DB::table('payrolls')
                        ->where('month', $monthInput)
                        ->where('year', $yearInput)
                        ->where('filename', $path . '/' . $filename)
                        ->delete();

                    if ($delete) {
                        if (Storage::exists($path . '/' . $filename)) {
                            Storage::delete($path . '/' . $filename);
                        }
                    }

                    rename(public_path('storage/media/payrollsTemp/' . $filename), public_path('storage/media/payrolls/' . $yearInput . '/' . $monthInput . '/' . $filename));
                    $payroll = new Payroll();
                    $payroll->employee_user_id = Db::Table('employee_user')->where('user_id', '=', $userId)->where('employee_id', '=', $employeeId)->value('id');
                    $payroll->filename =  'storage/media/payrolls/' . $yearInput . '/' . $monthInput . '/' . $filename;
                    $payroll->year = $yearInput;
                    $payroll->month = $monthInput;
                    $payroll->save();
                } else {
                    if (Storage::exists('storage/media/payrollsTemp/' . $filename)) {
                        Storage::delete('storage/media/payrollsTemp/' . $filename);
                    }
                    $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                }
            } else {
                try {
                    if ($employeeId == "") {
                        $employee = new Employee();
                        $employee->dni = $dni;
                        $employee = $employee->save();

                        if ($employee) {
                            $employeeId = Employee::where('dni', $dni)->value('id');
                        }
                    }
                    $user = User::find($userId);
                    $user->employees()->attach($employeeId);
                } catch (\Throwable $th) {
                    $uploadError[] = "No se ha podido agregar la nómina de la empresa " . $nif . ", compruebe si la empresa no está creada aún.";
                    continue;
                }

                $employee = Db::Table('users')
                    ->select('employees.id')
                    ->join('employee_user', 'employee_user.user_id', '=', 'users.id')
                    ->join('employees', 'employee_user.employee_id', '=', 'employees.id')
                    ->where('users.nif', '=', $nif)
                    ->where('employees.dni', '=', $dni)
                    ->get();

                if (count($employee) > 0) {
                    if ($monthInput . $yearInput == substr($filename, 20, 7)) {

                        // delete if payroll it is already created

                        $delete = DB::table('payrolls')
                            ->where('month', $monthInput)
                            ->where('year', $yearInput)
                            ->where('filename', $path . '/' . $filename)
                            ->delete();

                        if ($delete) {
                            if (Storage::exists($path . '/' . $filename)) {
                                Storage::delete($path . '/' . $filename);
                            }
                        }

                        rename(public_path('storage/media/payrollsTemp/' . $filename), public_path('storage/media/payrolls/' . $yearInput . '/' . $monthInput . '/' . $filename));
                        $payroll = new Payroll();
                        $payroll->employee_user_id = Db::Table('employee_user')->where('user_id', '=', $userId)->where('employee_id', '=', $employeeId)->value('id');
                        $payroll->filename =  'storage/media/payrolls/' . $yearInput . '/' . $monthInput . '/' . $filename;
                        $payroll->year = $yearInput;
                        $payroll->month = $monthInput;
                        $payroll->save();
                    } else {
                        if (Storage::exists('storage/media/payrollsTemp/' . $filename)) {
                            Storage::delete('storage/media/payrollsTemp/' . $filename);
                        }
                        $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                    }
                } else {
                    $uploadError[] = "No se ha podido agregar la nómina de la empresa " . $nif . ", compruebe si la empresa no está creada aún.";
                }
            }
        }

        if (Storage::directoryExists('storage/media/payrollsTemp')) {
            $delete = Storage::deleteDirectory('storage/media/payrollsTemp');
            if ($delete) {
                Storage::makeDirectory('storage/media/payrollsTemp', 0775, true);
            }
        }

        Mail::to(ENV('MAIL_TO_ADDRESS'))->send(new UploadPayrollsNotification($uploadError, $monthInput, $yearInput));
    }

    /**
     * The job failed to process.
     *
     * @param  Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        if (Storage::directoryExists('storage/media/payrollsTemp')) {
            $delete = Storage::deleteDirectory('storage/media/payrollsTemp');
            if ($delete) {
                Storage::makeDirectory('storage/media/payrollsTemp', 0775, true);
            }
        }


        $files = glob(public_path('storage/media/*.*'));
        foreach ($files as $index) {
            if (Storage::exists('storage/media/' . basename($index))) {
                Storage::delete('storage/media/' . basename($index));
            }
        }

        $jobError = "Error en la carga de Nóminas, vuelva a intentarlo gracias ;)";
        Mail::to(ENV('MAIL_TO_ADDRESS'))->send(new JobErrorNotification($jobError, $exception));
    }
}
