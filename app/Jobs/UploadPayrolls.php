<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use setasign\Fpdi\Fpdi;
use App\Models\Employee;
use App\Models\Payroll;
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
        $file = pathinfo($filename, PATHINFO_FILENAME);

        for ($i = 1; $i <= $pageCount; $i++) {
            $newPdf = new Fpdi();
            $newPdf->addPage();
            $newPdf->setSourceFile(public_path('storage/media/' . $filename));
            $newPdf->useTemplate($newPdf->importPage($i));
            $newFilename = sprintf('%s/%s_%s.pdf', 'storage/media/payrollsTemp', $file, $i);
            $newPdf->output($newFilename, 'F');
        }

        // end

        if (Storage::exists('storage/media/' . $filename)) {
            Storage::delete('storage/media/' . $filename);
        }


        // read and rename each .pdf

        $files = glob('storage/media/payrollsTemp/*.*');

        foreach ($files as $index) {
            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile($index);
            $content = $pdf->getText();


            preg_match_all('/MENS\s+[0-9]{2}\s+[A-Z]{3}\s+[0-9]{2}/', $content, $period, PREG_OFFSET_CAPTURE);
            preg_match_all('/[0-9]{8}[A-Z]/', $content, $dni, PREG_OFFSET_CAPTURE);
            preg_match_all('/[A-Z]{1}[0-9]{8}/', $content, $cif, PREG_OFFSET_CAPTURE);
            preg_match_all('/[X-Z]{1}[0-9]{7}[A-Z]{1}/', $content, $nie, PREG_OFFSET_CAPTURE);

            try {
                $month = substr($period[0][0][0], 9, 3);
                $year = substr($period[0][0][0], 13);


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

                $oldFilename = basename($index);
                rename('storage/media/payrollsTemp/' . $oldFilename, 'storage/media/payrollsTemp/' . $NIF . '_' .  $DNI . '_' . $month . 20 . $year . '.pdf');
            } catch (\Throwable $th) {
                $uploadError = "Error en las fechas/identificación de la nómina";
                continue;
            }
        }

        // delete temp files

        $files = glob('storage/media/payrollsTemp/' . basename($filename, '.pdf') . '_*.*');
        foreach ($files as $file) {
            if (Storage::exists($file)) {
                Storage::delete($file);
            }
        }

        // End

        // move to month and year folder

        $path = 'storage/media/payrolls/' . $yearInput;
        $files = glob('storage/media/payrollsTemp/*');
        $uploadError = array();

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

        foreach ($files as $file) {
            $filename = basename($file);
            $nif = substr($filename, 0, 9);
            $dni = substr($filename, 10, 9);

            // delete if the payroll is already created

            $delete = DB::table('payrolls')
                ->where('month', $monthInput)
                ->where('year', $yearInput)
                ->where('filename', $file)
                ->delete();

            if ($delete) {
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }

            // check if the employee is already created or not

            if (Employee::where('dni', '=', $dni)->exists()) {
                if ($monthInput . $yearInput == substr($filename, 20, 7)) {
                    rename('storage/media/payrollsTemp/' . $filename, 'storage/media/payrolls/' . $yearInput . '/' . $monthInput . '/' . $filename);
                    $payroll = new Payroll();
                    $payroll->employee_id = Db::Table('employees')->where('dni', '=',  $dni)->value('id');
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
                    $employee = new Employee();
                    $userId = Db::Table('users')->where('nif', '=',  $nif)->value('id');
                    $employee->user_id = $userId;
                    $employee->dni = $dni;
                    $employee->save();
                } catch (\Throwable $th) {
                    $uploadError[] = "No se ha podido agregar la nómina de la empresa " . $nif . ", compruebe si la empresa no está creada aún.";
                    continue;
                }

                if ($monthInput . $yearInput == substr($filename, 20, 7) && Employee::where('dni', '=', $dni)->exists()) {
                    rename('storage/media/payrollsTemp/' . $filename, 'storage/media/payrolls/' . $yearInput . '/' . $monthInput . '/' . $filename);
                    $payroll = new Payroll();
                    $payroll->employee_id = Db::Table('employees')->where('dni', '=', $dni)->value('id');
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
            }
        }

        Mail::to(ENV('MAIL_TO_ADDRESS'))->send(new UploadPayrollsNotification($uploadError, $monthInput, $yearInput));

        $files = glob('storage/media/payrollsTemp/*.*');
        foreach ($files as $index) {
            if (Storage::exists($index)) {
                Storage::delete($index);
            }
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        $files = glob('storage/media/payrollsTemp/*.*');
        foreach ($files as $index) {
            if (Storage::exists($index)) {
                Storage::delete($index);
            }
        }

        $files = glob('storage/media/*.*');
        foreach ($files as $index) {
            if (Storage::exists($index)) {
                Storage::delete($index);
            }
        }

        $jobError = "Error en la carga de Nóminas, vuelva a intentarlo gracias ;)";
        Mail::to(ENV('MAIL_TO_ADDRESS'))->send(new JobErrorNotification($jobError, $exception));
    }
}
