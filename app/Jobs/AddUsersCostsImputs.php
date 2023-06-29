<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use setasign\Fpdi\Fpdi;
use App\Models\User;
use DB;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AddUsersNotification;
use App\Mail\JobErrorNotification;
use Exception;
use Illuminate\Support\Facades\Storage;

class AddUsersCostsImputs implements ShouldQueue
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
        $data = array();
        $oldNIF = "";
        $usersNifPass = array();

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile(public_path('storage/media/' . $filename));
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $file = date('d-m-Y his a', time());

        // Split each page into a new PDF
        for ($i = 1; $i <= $pageCount; $i++) {
            $newPdf = new Fpdi();
            $newPdf->addPage();
            $newPdf->setSourceFile(public_path('storage/media/' . $filename));
            $newPdf->useTemplate($newPdf->importPage($i));
            $newFilename = sprintf('%s/%s_%s.%s', public_path('storage/media/addUsersTemp'), $file, $i, $extension);
            $newPdf->output($newFilename, 'F');
        }

        // read each .pdf

        $files = glob(public_path('storage/media/addUsersTemp/*.*'));

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
                $uploadError[] = "Error en las fechas/identificación del modelo de imputación de costes";
            } else {
                $findme2 = 'Empre sa';
                $pos2 = strpos($content, $findme2);
                $company = substr($content, ($pos2 + 14), 35);

                if ($oldNIF != $NIF) {
                    $companyData = array();
                    $companyData[] = $NIF;
                    $companyData[] = $company;
                    $data[] = $companyData;
                }
                $oldNIF = $NIF;
            }
        }

        // delete temps files

        if (Storage::directoryExists('storage/media/addUsersTemp')) {
            $delete = Storage::deleteDirectory('storage/media/addUsersTemp');
            if ($delete) {
                Storage::makeDirectory('storage/media/addUsersTemp', 0775, true);
            }
        }

        // End

        foreach ($data as $index) {

            if (Db::Table('users')->where('nif', $index[0])->exists()) {
                log::info("aqui");
                $uploadError[] = "La empresa " . $index[0] . " ya está creada.";
            } else {

                $user = new User();
                $user->nif = $index[0];
                $user->name = $index[1];
                $user->email = "email@email.es";
                $password = Str::random(10);
                $user->password = $password;

                $userNifPass = array(
                    'nif' => $index[0],
                    'password' => $password,
                );

                $usersNifPass[] = $userNifPass;
                $user->save();
                $user->assignRole('user');
            }
        }

        Mail::to(ENV('MAIL_TO_ADDRESS'))->send(new AddUsersNotification($usersNifPass, $uploadError));
    }

    /**
     * The job failed to process.
     *
     * @param  Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        if (Storage::directoryExists('storage/media/addUsersTemp')) {
            $delete = Storage::deleteDirectory('storage/media/addUsersTemp');
            if ($delete) {
                Storage::makeDirectory('storage/media/addUsersTemp', 0775, true);
            }
        }

        $files = glob(public_path('storage/media/*.*'));
        foreach ($files as $index) {
            if (Storage::exists('storage/media/' . basename($index))) {
                Storage::delete('storage/media/' . basename($index));
            }
        }

        $jobError = "Error en la creación de empresas, vuelva a intentarlo gracias ;)";
        Mail::to(ENV('MAIL_TO_ADDRESS'))->send(new JobErrorNotification($jobError, $exception));
    }
}
