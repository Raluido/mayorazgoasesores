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
use App\Models\User;
use DB;
use ZipArchive;
use Illuminate\Support\Facades\Auth;
use Payrolls;
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
        $month = $this->month;
        $year = $this->year;

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile(public_path('storage/media/temp/' . $filenamewithextension));
        $file = pathinfo($filenamewithextension, PATHINFO_FILENAME);

        // Split each page into a new PDF
        for ($i = 1; $i <= $pageCount; $i++) {
            $newPdf = new Fpdi();
            $newPdf->addPage();
            $newPdf->setSourceFile(public_path('storage/media/temp/' . $filenamewithextension));
            $newPdf->useTemplate($newPdf->importPage($i));
            $newFilename = sprintf('%s/%s_%s.pdf', public_path('storage/media/temp'), $file, $i);
            $newPdf->output($newFilename, 'F');
        }

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

            $findme1 = 'D.N.I.';
            $pos1 = strpos($content, $findme1);
            $Dni = substr($content, ($pos1 + 94), 11);

            $findme2 = 'PERIODO';
            $pos2 = strpos($content, $findme2);
            $monthYear = substr($content, ($pos2 + 79), 6);
            $monthYearFix = str_replace(' ', '', $monthYear);

            rename(public_path('storage/media/temp/' . $fileNameNoExt . '_' . $i . '.pdf'), public_path('storage/media/renamedPayrolls/' . $monthYearFix . '_' . $Nif . '_' . $Dni . '.pdf'));
        }

        $files = glob(public_path('storage/media/temp/*'));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // move to month and year folder

        switch ($month) {
            case 'Enero':
                $monthFix = 'ENE';
                break;
            case 'Febrero':
                $monthFix = 'FEB';
                break;
            case 'Marzo':
                $monthFix = 'MAR';
                break;
            case 'Abril':
                $monthFix = 'ABR';
                break;
            case 'Mayo':
                $monthFix = 'MAY';
                break;
            case 'Junio':
                $monthFix = 'JUN';
                break;
            case 'Julio':
                $monthFix = 'JUL';
                break;
            case 'Agosto':
                $monthFix = 'AGO';
                break;
            case 'Septiembre':
                $monthFix = 'SEP';
                break;
            case 'Octubre':
                $monthFix = 'OCT';
                break;
            case 'Noviembre':
                $monthFix = 'NOV';
                break;
            case 'Diciembre':
                $monthFix = 'DIC';
                break;
        }

        $path = public_path('/storage/media/' . $monthFix . $year);

        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true);

            $files = glob(public_path('storage/media/renamedPayrolls/*'));

            $uploadError = array();

            foreach ($files as $file) {
                $filenamewithextension = basename($file);
                $filenamewithoutextension = basename($file, ".pdf");
                $filenamewithoutextensionTrm = preg_replace('/\s+/', '', $filenamewithoutextension);
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                if ($monthFix . $year == substr($filename, 0, 5)) {
                    rename(public_path('storage/media/renamedPayrolls/' . $filename . '.pdf'), public_path('storage/media/' .  $monthFix . $year . '/' . $filenamewithoutextensionTrm . '.pdf'));
                    $payroll = new Payroll();
                    $payroll->nif = substr($filenamewithoutextensionTrm, 6, 9);
                    $payroll->dni = substr($filenamewithoutextensionTrm, 16, 9);
                    // $name = DB::Table('users')->where('nif', $payroll->nif)->value('name');
                    // $payroll->name = $name;
                    $payroll->filename = $filenamewithoutextensionTrm . '.pdf';
                    $payroll->monthyear = $monthFix . $year;
                    $payroll->save();
                } else {
                    echo '<div class="alert alert-warning"><strong>Warning!</strong>' . 'El archivo ' . $filenamewithoutextensionTrm . ' no pertenece al mes ' . $month . '.' . '</div>';
                    $uploadError[] = $filename;
                }
            }

            Mail::to("raluido@gmail.com")->send(new UploadPayrollsNotification($uploadError));
        } else {

            $files = glob(public_path('storage/media/renamedPayrolls/*'));

            foreach ($files as $file) {
                $filenamewithextension = basename($file);
                $filenamewithoutextension = basename($file, ".pdf");
                $filenamewithoutextensionTrm = preg_replace('/\s+/', '', $filenamewithoutextension);
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                if ($monthFix . $year == substr($filename, 0, 5)) {
                    if (File::exists($path . '/' . $filenamewithoutextensionTrm . '.pdf')) {
                        rename(public_path('storage/media/renamedPayrolls/' . $filename . '.pdf'), public_path('storage/media/' .  $monthFix . $year . '/' . $filenamewithoutextensionTrm . '.pdf'));
                        Payroll::where('filename', $filenamewithoutextensionTrm . '.pdf')->delete();
                        $payroll = new Payroll();
                        $payroll->nif = substr($filenamewithoutextensionTrm, 6, 9);
                        $payroll->dni = substr($filenamewithoutextensionTrm, 16, 9);
                        // $name = DB::Table('users')->where('nif', $payroll->nif)->value('name');
                        // $payroll->name = $name;
                        $payroll->filename = $filenamewithoutextensionTrm . '.pdf';
                        $payroll->monthyear = $monthFix . $year;
                        $payroll->save();
                    } else {
                        rename(public_path('storage/media/renamedPayrolls/' . $filename . '.pdf'), public_path('storage/media/' .  $monthFix . $year . '/' . $filenamewithoutextensionTrm . '.pdf'));
                        $payroll = new Payroll();
                        $payroll->nif = substr($filenamewithoutextensionTrm, 6, 9);
                        $payroll->dni = substr($filenamewithoutextensionTrm, 16, 9);
                        // $name = DB::Table('users')->where('nif', $payroll->nif)->value('name');
                        // $payroll->name = $name;
                        $payroll->filename = $filenamewithoutextensionTrm . '.pdf';
                        $payroll->monthyear = $monthFix . $year;
                        $payroll->save();
                    }
                }
            }
            $uploadError = array();
            $uploadError = "Todas las nóminas se han subido correctamente!!";

            Mail::to("raluido@gmail.com")->send(new UploadPayrollsNotification($uploadError));
        }
    }
}
