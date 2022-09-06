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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\UploadPayrollsNotification;
use Illuminate\Support\Facades\Mail;


class UploadPayrolls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filenamewithextension;
    protected $month;
    protected $year;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filenamewithextension, $month, $year)
    {
        $this->filenamewithextension = $filenamewithextension;
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

        $filenamewithextension = $this->filenamewithextension;
        $monthInput = $this->month;
        $yearInput = $this->year;

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile(public_path('storage/media/' . $filenamewithextension));
        $file = pathinfo($filenamewithextension, PATHINFO_FILENAME);

        // Split each page into a new PDF
        for ($i = 1; $i <= $pageCount; $i++) {
            $newPdf = new Fpdi();
            $newPdf->addPage();
            $newPdf->setSourceFile(public_path('storage/media/' . $filenamewithextension));
            $newPdf->useTemplate($newPdf->importPage($i));
            $newFilename = sprintf('%s/%s_%s.pdf', public_path('storage/media/temp'), $file, $i);
            $newPdf->output($newFilename, 'F');
        }

        unlink(public_path('storage/media/' . $filenamewithextension));

        // read and rename each .pdf
        $fileNameNoExt = pathinfo($filenamewithextension, PATHINFO_FILENAME);

        for ($i = 1; $i <= $pageCount; $i++) {
            $path = public_path('storage/media/temp/' . $fileNameNoExt . '_' . $i . '.pdf');
            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile($path);
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
            $uploadError = array(null);

            if (in_array($NifFix[0], $abc) || in_array($NifFix[8], $abc) && in_array($DniFix[8], $abc)) {
                rename(public_path('storage/media/temp/' . $fileNameNoExt . '_' . $i . '.pdf'), public_path('storage/media/renamedPayrolls/' . $NifFix . '_' . $DniFix . '_' . $month . $year . '.pdf'));
            } else {
                $uploadError[] = 'El ' . $NifFix . 'o' . $DniFix . 'han dado error de forma, consulte al administrador de sistema.';
            }
        }

        $files = glob(public_path('storage/media/temp/*'));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // move to month and year folder

        $path = public_path('/storage/media/payrolls/' . $year);

        $uploadError = array(null);

        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true);
            $path = public_path('/storage/media/payrolls/' . $year . '/' . $month);
            File::makeDirectory($path, 0777, true);
            $files = glob(public_path('storage/media/renamedPayrolls/*'));

            foreach ($files as $file) {
                $filenamewithextension = basename($file);
                $filenamewithoutextension = basename($file, ".pdf");
                // $filenamewithoutextensionTrm = preg_replace('/\s+/', '', $filenamewithoutextension);
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                // check if the employee is already created or not

                if (Employee::where('dni', substr($filenamewithoutextension, 10, 9))->exists()) {
                } else {
                    try {
                        $employee = new Employee();
                        $userId = Db::Table('users')->where('nif', substr($filenamewithoutextension, 0, 9))->value('id');
                        $employee->user_id = $userId;
                        $employee->dni = substr($filenamewithoutextension, 10, 9);
                        $employee->save();
                    } catch (\Throwable $th) {
                        $uploadError[] = "No se ha podido agregar la nómina de la empresa " . substr($filenamewithoutextension, 0, 9) . ", compruebe si no está creada aún.";
                        break;
                    }
                }

                if ($monthInput . $yearInput == substr($filename, 20, 7)) {
                    rename(public_path('storage/media/renamedPayrolls/' . $filename . '.pdf'), public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $filenamewithoutextension . '.pdf'));
                    $payroll = new Payroll();
                    $payroll->employee_id = Db::Table('employees')->where('dni', substr($filenamewithoutextension, 10, 9))->value('id');
                    $payroll->filename = $filenamewithoutextension . '.pdf';
                    $payroll->year = $year;
                    $payroll->month = $month;
                    $payroll->save();
                } else {
                    unlink(public_path('storage/media/renamedPayrolls/' . $filename . '.pdf'));
                    $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                }
            }
        } else {
            $path = public_path('/storage/media/payrolls/' . $year . '/' . $month);
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);

                $files = glob(public_path('storage/media/renamedPayrolls/*'));

                foreach ($files as $file) {
                    $filenamewithextension = basename($file);
                    $filenamewithoutextension = basename($file, ".pdf");
                    // $filenamewithoutextensionTrm = preg_replace('/\s+/', '', $filenamewithoutextension);
                    $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                    // check if the employee is already created or not

                    if (Employee::where('dni', substr($filenamewithoutextension, 10, 9))->exists()) {
                    } else {
                        try {
                            $employee = new Employee();
                            $userId = Db::Table('users')->where('nif', substr($filenamewithoutextension, 0, 9))->value('id');
                            $employee->user_id = $userId;
                            $employee->dni = substr($filenamewithoutextension, 10, 9);
                            $employee->save();
                        } catch (\Throwable $th) {
                            $uploadError[] = "No se ha podido agregar la nómina de la empresa " . substr($filenamewithoutextension, 0, 9) . ", compruebe si no está creada aún.";
                            break;
                        }
                    }

                    if ($monthInput . $yearInput == substr($filename, 20, 7)) {
                        if (File::exists($path . '/' . $filenamewithoutextension . '.pdf')) {
                            rename(public_path('storage/media/renamedPayrolls/' . $filename . '.pdf'), public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $filenamewithoutextension . '.pdf'));
                            Payroll::where('filename', $filenamewithoutextension . '.pdf')->delete();
                            $payroll = new Payroll();
                            $payroll->employee_id = Db::Table('employees')->where('dni', substr($filenamewithoutextension, 10, 9))->value('id');
                            $payroll->filename = $filenamewithoutextension . '.pdf';
                            $payroll->year = $year;
                            $payroll->month = $month;
                            $payroll->save();
                        } else {
                            rename(public_path('storage/media/renamedPayrolls/' . $filename . '.pdf'), public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $filenamewithoutextension . '.pdf'));
                            $payroll = new Payroll();
                            $payroll->employee_id = Db::Table('employees')->where('dni', substr($filenamewithoutextension, 10, 9))->value('id');
                            $payroll->filename = $filenamewithoutextension . '.pdf';
                            $payroll->year = $year;
                            $payroll->month = $month;
                            $payroll->save();
                        }
                    } else {
                        unlink(public_path('storage/media/renamedPayrolls/' . $filename . '.pdf'));
                        $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                    }
                }
            } else {
                $files = glob(public_path('storage/media/renamedPayrolls/*'));

                foreach ($files as $file) {
                    $filenamewithextension = basename($file);
                    $filenamewithoutextension = basename($file, ".pdf");
                    $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                    // check if the employee is already created or not

                    if (Employee::where('dni', substr($filenamewithoutextension, 10, 9))->exists()) {
                    } else {
                        try {
                            $employee = new Employee();
                            $userId = Db::Table('users')->where('nif', substr($filenamewithoutextension, 0, 9))->value('id');
                            $employee->user_id = $userId;
                            $employee->dni = substr($filenamewithoutextension, 10, 9);
                            $employee->save();
                        } catch (\Throwable $th) {
                            $uploadError[] = "No se ha podido agregar la nómina de la empresa " . substr($filenamewithoutextension, 0, 9) . ", compruebe si no está creada aún.";
                            break;
                        }
                    }

                    if ($monthInput . $yearInput == substr($filename, 20, 7)) {
                        if (File::exists($path . '/' . $filenamewithoutextension . '.pdf')) {
                            rename(public_path('storage/media/renamedPayrolls/' . $filename . '.pdf'), public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $filenamewithoutextension . '.pdf'));
                            Payroll::where('filename', $filenamewithoutextension . '.pdf')->delete();
                            $payroll = new Payroll();
                            $payroll->employee_id = Db::Table('employees')->where('dni', substr($filenamewithoutextension, 10, 9))->value('id');
                            $payroll->filename = $filenamewithoutextension . '.pdf';
                            $payroll->year = $year;
                            $payroll->month = $month;
                            $payroll->save();
                        } else {
                            rename(public_path('storage/media/renamedPayrolls/' . $filename . '.pdf'), public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $filenamewithoutextension . '.pdf'));
                            $payroll = new Payroll();
                            $payroll->employee_id = Db::Table('employees')->where('dni', substr($filenamewithoutextension, 10, 9))->value('id');
                            $payroll->filename = $filenamewithoutextension . '.pdf';
                            $payroll->year = $year;
                            $payroll->month = $month;
                            $payroll->save();
                        }
                    } else {
                        unlink(public_path('storage/media/renamedPayrolls/' . $filename . '.pdf'));
                        $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                    }
                }
            }
        }

        if ($uploadError[0] == null) {
            $uploadError[0] = 'Todas las nóminas se han subido correctamente';
        }

        Mail::to("raluido@gmail.com")->send(new UploadPayrollsNotification($uploadError));
    }
}
