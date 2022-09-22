<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use setasign\Fpdi\Fpdi;
use App\Models\Payroll;
use App\Models\Employee;
use DB;
use ZipArchive;
use Illuminate\Support\Facades\Auth;
use Smalot\PdfParser\Parser;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\UploadPayrollsNotification;
use App\Mail\JobErrorNotification;
use Illuminate\Support\Facades\Mail;
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

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile(public_path('storage/media/' . $filename));
        $file = pathinfo($filename, PATHINFO_FILENAME);

        // Split each page into a new PDF
        for ($i = 1; $i <= $pageCount; $i++) {
            $newPdf = new Fpdi();
            $newPdf->addPage();
            $newPdf->setSourceFile(public_path('storage/media/' . $filename));
            $newPdf->useTemplate($newPdf->importPage($i));
            $newFilename = sprintf('%s/%s_%s.pdf', public_path('storage/media/payrollsTemp'), $file, $i);
            $newPdf->output($newFilename, 'F');
        }

        unlink(public_path('storage/media/' . $filename));

        // read and rename each .pdf
        $files = glob(public_path('storage/media/payrollsTemp/*'));


        foreach ($files as $index) {
            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile($index);
            $content = $pdf->getText();

            $findme = 'NIF. ';
            $pos = strpos($content, $findme);
            $Nif = substr($content, ($pos + 5), 9);
            $NifFix = preg_replace('/\s+/', '', $Nif);

            $findme1 = 'D.N.I.';
            $pos1 = strpos($content, $findme1);
            $Dni = substr($content, ($pos1 + 94), 11);
            $DniFix = preg_replace('/\s+/', '', $Dni);

            $findme2 = 'PERIODO';
            $pos2 = strpos($content, $findme2);
            $month = substr($content, ($pos2 + 79), 3);
            $year = '20' . substr($content, ($pos2 + 83), 2);

            // check if the nif format is correct
            $abc = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'Ñ', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            $num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $uploadError = array();

            $true = 0;
            $oldFilename = basename($index);

            if (in_array($NifFix[0], $abc)) {
                for ($i = 1; $i < 8; $i++) {
                    if (in_array($NifFix[$i], $num)) {
                        $true++;
                    } else {
                        $uploadError[] = 'El ' . $NifFix . 'ha dado error de forma, consule al administrador de sistema.';
                        break;
                    }
                }
                if (true == 8) {
                    $true = 0;
                    if (in_array($DniFix[8], $abc)) {
                        for ($i = 0; $i < 7; $i++) {
                            if (in_array($NifFix[$i], $num)) {
                                $true++;
                            } else {
                                $uploadError[] = 'El ' . $DniFix . 'ha dado error de forma, consule al administrador de sistema.';
                                break;
                            }
                        }
                        if (true == 8) {
                            rename(public_path('storage/media/payrollsTemp/' . $oldFilename), public_path('storage/media/payrollsTemp/' . $NifFix . '_' .  $DniFix . '_' . $month . $year . '.pdf'));
                        }
                    } else {
                        $uploadError[] = 'El ' . $DniFix . 'ha dado error de forma, consule al administrador de sistema.';
                    }
                }
            } elseif (in_array($NifFix[8], $abc)) {
                for ($i = 0; $i < 7; $i++) {
                    if (in_array($NifFix[$i], $num)) {
                        $true++;
                    } else {
                        $uploadError[] = 'El ' . $NifFix . 'ha dado error de forma, consule al administrador de sistema.';
                        break;
                    }
                }
                if (true == 8) {
                    $true = 0;
                    if (in_array($DniFix[8], $abc)) {
                        for ($i = 0; $i < 7; $i++) {
                            if (in_array($NifFix[$i], $num)) {
                                $true++;
                            } else {
                                $uploadError[] = 'El ' . $DniFix . 'ha dado error de forma, consule al administrador de sistema.';
                                break;
                            }
                        }
                        if (true == 8) {
                            rename(public_path('storage/media/payrollsTemp/' . $oldFilename), public_path('storage/media/payrollsTemp/' . $NifFix . '_' .  $DniFix . '_' . $month . $year . '.pdf'));
                        }
                    } else {
                        $uploadError[] = 'El ' . $DniFix . 'ha dado error de forma, consule al administrador de sistema.';
                    }
                }
            } else {
                $uploadError[] = 'El ' . $NifFix . 'ha dado error de forma, consule al administrador de sistema.';
            }
        }

        $files = glob(public_path('storage/media/payrollsTemp/' . $filename . '_' . '*.*'));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // move to month and year folder

        $path = public_path('/storage/media/payrolls/' . $year);
        $files = glob(public_path('storage/media/payrollsTemp/*'));
        $uploadError = array();

        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true);
            $path = public_path('/storage/media/payrolls/' . $year . '/' . $month);
            File::makeDirectory($path, 0777, true);

            foreach ($files as $file) {
                $filename = basename($file);
                $nif = substr($filename, 0, 9);
                $dni = substr($filename, 10, 9);

                // check if the employee is already created or not

                if (Employee::where('dni', '=', $dni)->exists()) {
                    if ($monthInput . $yearInput == substr($filename, 20, 7)) {
                        rename(public_path('storage/media/payrollsTemp/' . $filename), public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $filename));
                        $payroll = new Payroll();
                        $payroll->employee_id = Db::Table('employees')->where('dni', '=',  $dni)->value('id');
                        $payroll->filename = $path . '/' . $filename;
                        $payroll->year = $year;
                        $payroll->month = $month;
                        $payroll->save();
                    } else {
                        unlink(public_path('storage/media/payrollsTemp/' . $filename));
                        $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                    }
                } else {
                    try {
                        $employee = new Employee();
                        $userId = Db::Table('users')->where('nif', '=',  $nif)->value('id');
                        $employee->user_id = $userId;
                        $employee->dni = $dni;
                        $employee->save();

                        if ($monthInput . $yearInput == substr($filename, 20, 7) && Employee::where('dni', '=', $dni)->exists()) {
                            rename(public_path('storage/media/payrollsTemp/' . $filename), public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $filename));
                            $payroll = new Payroll();
                            $payroll->employee_id = Db::Table('employees')->where('dni', '=', $dni)->value('id');
                            $payroll->filename = $path . '/' . $filename;
                            $payroll->year = $year;
                            $payroll->month = $month;
                            $payroll->save();
                        } else {
                            unlink(public_path('storage/media/payrollsTemp/' . $filename));
                            $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                        }
                    } catch (\Throwable $th) {
                        $uploadError[] = "No se ha podido agregar la nómina de la empresa " . $nif . ", compruebe si la empresa no está creada aún.";
                        continue;
                    }
                }
            }
        } else {
            $path = public_path('/storage/media/payrolls/' . $year . '/' . $month);
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);

                $i = 0;

                foreach ($files as $file) {
                    $filename = basename($file);
                    $nif = substr($filename, 0, 9);
                    $dni = substr($filename, 10, 9);

                    // check if the employee is already created or not

                    if (Employee::where('dni', '=', $dni)->exists()) {
                        if ($monthInput . $yearInput == substr($filename, 20, 7)) {
                            rename(public_path('storage/media/payrollsTemp/' . $filename), public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $filename));
                            $payroll = new Payroll();
                            $payroll->employee_id = Db::Table('employees')->where('dni', '=', $dni)->value('id');
                            $payroll->filename = $path . '/' . $filename;
                            $payroll->year = $year;
                            $payroll->month = $month;
                            $payroll->save();
                        } else {
                            unlink(public_path('storage/media/payrollsTemp/' . $filename));
                            $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                        }
                    } else {
                        try {
                            $employee = new Employee();
                            $userId = Db::Table('users')->where('nif', '=', $nif)->value('id');
                            $employee->user_id = $userId;
                            $employee->dni = $dni;
                            $employee->save();

                            if ($monthInput . $yearInput == substr($filename, 20, 7) && Employee::where('dni', '=', $dni)->exists()) {
                                rename(public_path('storage/media/payrollsTemp/' . $filename), public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $filename));
                                $payroll = new Payroll();
                                $payroll->employee_id = Db::Table('employees')->where('dni', '=', $dni)->value('id');
                                $payroll->filename = $path . '/' . $filename;
                                $payroll->year = $year;
                                $payroll->month = $month;
                                $payroll->save();
                            } else {
                                unlink(public_path('storage/media/payrollsTemp/' . $filename));
                                $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                            }
                        } catch (\Throwable $th) {
                            $uploadError[] = "No se ha podido agregar la nómina de la empresa " . $nif . ", compruebe si la empresa no está creada aún.";
                            continue;
                        }
                    }
                }
            } else {
                $path = public_path('/storage/media/payrolls/' . $year . '/' . $month);

                foreach ($files as $file) {
                    $filename = basename($file);
                    $nif = substr($filename, 0, 9);
                    $dni = substr($filename, 10, 9);

                    // check if the employee is already created or not

                    if (Employee::where('dni', '=', $dni)->exists()) {
                        if ($monthInput . $yearInput == substr($filename, 20, 7)) {
                            rename(public_path('storage/media/payrollsTemp/' . $filename), public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $filename));
                            $payroll = new Payroll();
                            $payroll->employee_id = Db::Table('employees')->where('dni', '=', $dni)->value('id');
                            $payroll->filename = $path . '/' . $filename;
                            $payroll->year = $year;
                            $payroll->month = $month;
                            $payroll->save();
                        } else {
                            unlink(public_path('storage/media/payrollsTemp/' . $filename));
                            $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                        }
                    } else {
                        try {
                            $employee = new Employee();
                            $userId = Db::Table('users')->where('nif', '=', $nif)->value('id');
                            $employee->user_id = $userId;
                            $employee->dni = $dni;
                            $employee->save();

                            if ($monthInput . $yearInput == substr($filename, 20, 7) && Employee::where('dni', '=', $dni)->exists()) {
                                rename(public_path('storage/media/payrollsTemp/' . $filename), public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $filename));
                                $payroll = new Payroll();
                                $payroll->employee_id = Db::Table('employees')->where('dni', '=', $dni)->value('id');
                                $payroll->filename = $path . '/' . $filename;
                                $payroll->year = $year;
                                $payroll->month = $month;
                                $payroll->save();
                            } else {
                                unlink(public_path('storage/media/payrollsTemp/' . $filename));
                                $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                            }
                        } catch (\Throwable $th) {
                            $uploadError[] = "No se ha podido agregar la nómina de la empresa " . $nif . ", compruebe si la empresa no está creada aún.";
                            continue;
                        }
                    }
                }
            }
        }

        Mail::to("raluido@gmail.com")->send(new UploadPayrollsNotification($uploadError, $monthInput, $yearInput));

        array_map('unlink', glob(public_path('storage/media/payrollsTemp/' . '*.*')));
    }

    /**
     * The job failed to process.
     *
     * @param  Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        array_map('unlink', glob(public_path('storage/media/payrollsTemp/' . '*.*')));

        $jobError = "Error en la carga de Nóminas, vuelva a intentarlo gracias ;)";
        Mail::to("raluido@gmail.com")->send(new JobErrorNotification($jobError, $exception));
    }
}
