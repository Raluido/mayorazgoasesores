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
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\UploadCostsImputsNotification;
use App\Mail\JobErrorNotification;
use Exception;

class UploadCostsImputs implements ShouldQueue
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
        $oldNif = "";
        $x = 0;

        // Split each page into a new PDF

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile(public_path('storage/media/' . $filename));
        $file = pathinfo($filename, PATHINFO_FILENAME);

        for ($i = 1; $i <= $pageCount; $i++) {
            $newPdf = new Fpdi();
            $newPdf->addPage();
            $newPdf->setSourceFile(public_path('storage/media/' . $filename));
            $newPdf->useTemplate($newPdf->importPage($i));
            $newFilename = sprintf('%s/%s_%s.pdf', public_path('storage/media/costsImputsTemp'), $file, $i);
            $newPdf->output($newFilename, 'F');
        }

        // End

        unlink(public_path('storage/media/' . $filename));

        // read and rename each .pdf

        $files = glob(public_path('storage/media/costsImputsTemp/*.*'));

        foreach ($files as $index) {
            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile($index);
            $content = $pdf->getText();

            preg_match_all('/PERIODO\s+DEL\s+[0-9]{2}\/[0-9]{2}\/[0-9]{2}/', $content, $period, PREG_OFFSET_CAPTURE);
            preg_match_all('/[0-9]{8}[A-Z]/', $content, $dni, PREG_OFFSET_CAPTURE);
            preg_match_all('/[A-Z]{1}[0-9]{8}/', $content, $cif, PREG_OFFSET_CAPTURE);
            preg_match_all('/[X-Z]{1}[0-9]{7}[A-Z]{1}/', $content, $nie, PREG_OFFSET_CAPTURE);

            try {
                $month = substr($period[0][0][0], 15, 2);
                $year = substr($period[0][0][0], 18, 2);

                if (count($cif[0]) == 1) {
                    $NIF = $cif[0][0][0];
                } elseif (count($dni[0]) == 1) {
                    $NIF = $dni[0][0][0];
                } elseif (count($nie[0]) == 1) {
                    $NIF = $nie[0][0][0];
                }
            } catch (\Throwable $th) {
                $month = "";
                $year = "";
                $NIF = "";
                continue;
            }

            switch ($month) {
                case '01':
                    $month = 'ENE';
                    break;
                case '02':
                    $month = 'FEB';
                    break;
                case '03':
                    $month = 'MAR';
                    break;
                case '04':
                    $month = 'ABR';
                    break;
                case '05':
                    $month = 'MAY';
                    break;
                case '06':
                    $month = 'JUN';
                    break;
                case '07':
                    $month = 'JUL';
                    break;
                case '08':
                    $month = 'AGO';
                    break;
                case '09':
                    $month = 'SEP';
                    break;
                case '10':
                    $month = 'OCT';
                    break;
                case '11':
                    $month = 'NOV';
                    break;
                case '12':
                    $month = 'DIC';
                    break;
                default:
                    $month = "";
                    break;
            }

            if ($month . '20' . $year != $monthInput . $yearInput) {
                $uploadError = "Error en las fechas/identificación del modelo de imputación de costes";
            } else {

                $oldFilename = basename($index);

                if ($oldNif != $NIF) {
                    $x = 0;
                } else {
                    $x++;
                }

                $oldNif = $NIF;
                rename(public_path('storage/media/costsImputsTemp/' . $oldFilename), public_path('storage/media/costsImputsTemp/' . $NIF . '_' . $month . 20 . $year . '_' . $x . '.pdf'));
            }
        }

        $files = glob(public_path('storage/media/costsImputsTemp/' . basename($filename, '.pdf') . '_*.*'));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // move to month/year folder

        $path = public_path('/storage/media/costsImputs/' . $yearInput);

        if (!File::exists($path)) {
            File::makeDirectory($path, 0775, true);
            $path = public_path('/storage/media/costsImputs/' . $yearInput . '/' . $monthInput);
            File::makeDirectory($path, 0775, true);
        } else {
            $path = public_path('/storage/media/costsImputs/' . $yearInput . '/' . $monthInput);
            if (!File::exists($path)) {
                File::makeDirectory($path, 0775, true);
            }
        }

        $files = glob(public_path('storage/media/costsImputsTemp/*'));

        foreach ($files as $file) {
            $filename = basename($file);
            $nif = substr($filename, 0, 9);

            if ($monthInput . $yearInput == substr($filename, 10, 7)) {
                rename(public_path('storage/media/costsImputsTemp/' . $filename), public_path('storage/media/costsImputs/' . $yearInput . '/' . $monthInput . '/' . $filename));
                $costsImput = new CostsImput();
                $costsImput->user_id = Db::Table('users')->where('nif', '=', $nif)->value('id');
                $costsImput->filename = $path . '/' . $filename;
                $costsImput->month = $monthInput;
                $costsImput->year = $yearInput;
                $costsImput->save();
            } else {
                unlink(public_path('storage/media/costsImputsTemp/' . $filename));
                $uploadError[] = 'Error, la fecha es incorrecta:' . ' ' . $filename;
            }
        }

        Mail::to(config('mail.to')['address'])->send(new UploadCostsImputsNotification($uploadError, $monthInput, $yearInput));

        $files = glob(public_path('storage/media/costsImputsTemp/*.*'));
        foreach ($files as $index) {
            if (is_file($index)) {
                unlink($index);
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
        $files = glob(public_path('storage/media/costsImputsTemp/*.*'));
        foreach ($files as $index) {
            unlink($index);
        }

        $files = glob(public_path('storage/media/*.*'));
        foreach ($files as $index) {
            unlink($index);
        }

        $jobError = "Error en la carga de Imputación de Costes, vuelva a intentarlo gracias ;)";
        Mail::to(config('mail.to')['address'])->send(new JobErrorNotification($jobError, $exception));
    }
}
