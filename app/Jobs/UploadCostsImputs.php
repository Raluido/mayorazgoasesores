<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use setasign\Fpdi\Fpdi;
use App\Models\CostsImput;
use App\Models\User;
use DB;
use ZipArchive;
use Illuminate\Support\Facades\Auth;
use Smalot\PdfParser\Parser;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\UploadCostsImputsNotification;
use Illuminate\Support\Facades\Mail;


class UploadCostsImputs implements ShouldQueue
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

            $findme = 'N.I.F.';
            $pos = strpos($content, $findme);
            $Nif = substr($content, ($pos - 39), 9);

            $findme2 = 'PERIODO';
            $pos2 = strpos($content, $findme2);
            $month = substr($content, ($pos2 + 15), 2);
            $year = substr($content, ($pos2 + 18), 2);

            switch ($month) {
                case '01':
                    $monthFix = 'ENE';
                    break;
                case '02':
                    $monthFix = 'FEB';
                    break;
                case '03':
                    $monthFix = 'MAR';
                    break;
                case '04':
                    $monthFix = 'ABR';
                    break;
                case '05':
                    $monthFix = 'MAY';
                    break;
                case '06':
                    $monthFix = 'JUN';
                    break;
                case '07':
                    $monthFix = 'JUL';
                    break;
                case '08':
                    $monthFix = 'AGO';
                    break;
                case '09':
                    $monthFix = 'SEP';
                    break;
                case 'Octubre':
                    $monthFix = 'OCT';
                    break;
                case '11':
                    $monthFix = 'NOV';
                    break;
                case '12':
                    $monthFix = 'DIC';
                    break;
            }

            rename(public_path('storage/media/temp/' . $fileNameNoExt . '_' . $i . '.pdf'), public_path('storage/media/renamedCostsImputs/' . $Nif . '_' . $monthFix . $year . '_' . $i . '.pdf'));
        }

        $files = glob(public_path('storage/media/temp/*'));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // move to month and year folder

        $path = public_path('/storage/media/costsImputs/' . '20' . $year);

        $uploadError = array(null);

        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true);
            $path = public_path('/storage/media/costsImputs/' . '20' . $year . '/' . $monthFix);
            File::makeDirectory($path, 0777, true);

            $files = glob(public_path('storage/media/renamedCostsImputs/*'));

            foreach ($files as $file) {
                $filenamewithextension = basename($file);
                $filenamewithoutextension = basename($file, ".pdf");
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                if ($monthFix . $year == substr($filename, 10, 5)) {
                    rename(public_path('storage/media/renamedCostsImputs/' . $filename . '.pdf'), public_path('storage/media/costsImputs/' . '20' . $year . '/' . $monthFix . '/' . $filenamewithoutextension . '.pdf'));
                    $costsImput = new CostsImput();
                    $costsImput->nif = substr($filenamewithoutextension, 0, 9);
                    $costsImput->filename = $filenamewithoutextension . '.pdf';
                    $costsImput->month = $monthFix;
                    $costsImput->year = $year;
                    $costsImput->save();
                } else {
                    unlink(public_path('storage/media/renamedCostsImputs/' . $filename . '.pdf'));
                    $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                }
            }
        } else {

            if (!File::exists($path)) {
                $path = public_path('/storage/media/costsImputs/' . '20' . $year . '/' . $monthFix);
                File::makeDirectory($path, 0777, true);

                $files = glob(public_path('storage/media/renamedCostsImputs/*'));

                foreach ($files as $file) {
                    $filenamewithextension = basename($file);
                    $filenamewithoutextension = basename($file, ".pdf");
                    $filenamewithoutextensionTrm = preg_replace('/\s+/', '', $filenamewithoutextension);
                    $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                    if ($monthFix . $year == substr($filename, 10, 5)) {
                        if (File::exists($path . '/' . $filenamewithoutextensionTrm . '.pdf')) {
                            rename(public_path('storage/media/renamedCostsImputs/' . $filename . '.pdf'), public_path('storage/media/costsImputs/' . '20' . $year . '/' . $monthFix . '/' . $filenamewithoutextension . '.pdf'));
                            CostsImput::where('filename', $filenamewithoutextension . '.pdf')->delete();
                            $costsImput = new CostsImput();
                            $costsImput->nif = substr($filenamewithoutextension, 0, 9);
                            $costsImput->filename = $filenamewithoutextension . '.pdf';
                            $costsImput->month = $monthFix;
                            $costsImput->year = $year;
                            $costsImput->save();
                        } else {
                            rename(public_path('storage/media/renamedCostsImputs/' . $filename . '.pdf'), public_path('storage/media/costsImputs/' . '20' . $year . '/' . $monthFix . '/' . $filenamewithoutextension . '.pdf'));
                            $costsImput = new CostsImput();
                            $costsImput->nif = substr($filenamewithoutextension, 0, 9);
                            $costsImput->filename = $filenamewithoutextensionTrm . '.pdf';
                            $costsImput->month = $monthFix;
                            $costsImput->year = $year;
                            $costsImput->save();
                        }
                    } else {
                        unlink(public_path('storage/media/renamedCostsImputs/' . $filename . '.pdf'));
                        $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                    }
                }
            } else {

                $files = glob(public_path('storage/media/renamedCostsImputs/*'));

                foreach ($files as $file) {
                    $filenamewithextension = basename($file);
                    $filenamewithoutextension = basename($file, ".pdf");
                    $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                    if ($monthFix . $year == substr($filename, 10, 5)) {
                        if (File::exists($path . '/' . $filenamewithoutextension . '.pdf')) {
                            rename(public_path('storage/media/renamedCostsImputs/' . $filename . '.pdf'), public_path('storage/media/costsImputs/' . '20' . $year . '/' . $monthFix . '/' . $filenamewithoutextension . '.pdf'));
                            CostsImput::where('filename', $filenamewithoutextension . '.pdf')->delete();
                            $costsImput = new CostsImput();
                            $costsImput->nif = substr($filenamewithoutextension, 0, 9);
                            $costsImput->filename = $filenamewithoutextension . '.pdf';
                            $costsImput->month = $monthFix;
                            $costsImput->year = $year;
                            $costsImput->save();
                        } else {
                            rename(public_path('storage/media/renamedCostsImputs/' . $filename . '.pdf'), public_path('storage/media/costsImputs/' . '20' . $year . '/' . $monthFix . '/' . $filenamewithoutextension . '.pdf'));
                            $costsImput = new CostsImput();
                            $costsImput->nif = substr($filenamewithoutextension, 0, 9);
                            $costsImput->filename = $filenamewithoutextension . '.pdf';
                            $costsImput->month = $monthFix;
                            $costsImput->year = $year;
                            $costsImput->save();
                        }
                    } else {
                        unlink(public_path('storage/media/renamedCostsImputs/' . $filename . '.pdf'));
                        $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                    }
                }
            }
        }

        if ($uploadError[0] == null) {
            $uploadError[0] = 'Todos los modelos de imputación de costes se han subido correctamente';
        }

        Mail::to("raluido@gmail.com")->send(new UploadCostsImputsNotification($uploadError));
    }
}
