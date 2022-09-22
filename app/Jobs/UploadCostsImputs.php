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
use App\Mail\JobErrorImputsNotification;
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

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile(public_path('storage/media/' . $filename));
        $file = pathinfo($filename, PATHINFO_FILENAME);

        // Split each page into a new PDF
        for ($i = 1; $i <= $pageCount; $i++) {
            $newPdf = new Fpdi();
            $newPdf->addPage();
            $newPdf->setSourceFile(public_path('storage/media/' . $filename));
            $newPdf->useTemplate($newPdf->importPage($i));
            $newFilename = sprintf('%s/%s_%s.pdf', public_path('storage/media/costsImputsTemp'), $file, $i);
            $newPdf->output($newFilename, 'F');
        }

        unlink(public_path('storage/media/' . $filename));

        // read and rename each .pdf
        $files = glob(public_path('storage/media/costsImputsTemp/*'));

        $oldNif = " ";
        $x = 0;

        foreach ($files as $index) {
            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile($index);
            $content = $pdf->getText();

            $findme = 'N.I.F.';
            $pos = strpos($content, $findme);
            $Nif = substr($content, ($pos - 39), 9);

            // fix nif

            $abc = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'Ñ', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            $num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

            try {
                if (ctype_space($Nif[0]) || ctype_space($Nif[1])) {
                    $Nif = substr($content, ($pos - 37), 9);
                }
            } catch (\Throwable $th) {
                log::info($Nif);
                break;
            }

            // en fix nif

            $findme2 = 'PERIODO';
            $pos2 = strpos($content, $findme2);
            $month = substr($content, ($pos2 + 15), 2);

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
            }

            $year = '20' . substr($content, ($pos2 + 18), 2);

            // check if the nif format is correct
            $abc = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'Ñ', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            $num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $uploadError = array();

            $true = 0;
            $oldFilename = basename($index);

            if (in_array($Nif[0], $abc)) {
                for ($i = 1; $i < 8; $i++) {
                    if (in_array($Nif[$i], $num)) {
                        $true++;
                    } else {
                        $uploadError[] = 'El ' . $Nif . 'ha dado error de forma, consule al administrador de sistema.';
                        break;
                    }
                }
                if (true == 8) {
                    if ($oldNif != $Nif) {
                        $x = 0;
                    } else {
                        $x++;
                    }
                    $oldNif = $Nif;
                    rename(public_path('storage/media/costsImputsTemp/' . $oldFilename), public_path('storage/media/costsImputsTemp/' . $Nif . '_' . $month . $year . '_' . $x . '.pdf'));
                }
            } else {
                if (in_array($Nif[8], $abc)) {
                    for ($i = 0; $i < 7; $i++) {
                        if (in_array($Nif[$i], $num)) {
                            $true++;
                        } else {
                            $uploadError[] = 'El ' . $Nif . ' ha dado error de forma, consule al administrador de sistema.';
                            break;
                        }
                    }
                    if (true == 8) {
                        if ($oldNif != $Nif) {
                            $x = 0;
                        } else {
                            $x = 0;
                            $x++;
                        }
                        $oldNif = $Nif;
                        rename(public_path('storage/media/costsImputsTemp/' . $oldFilename), public_path('storage/media/costsImputsTemp/' . $Nif . '_' . $month . $year . '_' . $x . '.pdf'));
                    }
                } else {
                    $uploadError[] = 'El ' . $Nif . ' ha dado error de forma, consule al administrador de sistema.';
                }
            }

            // end check if the nif format is correct
        }

        $files = glob(public_path('storage/media/costsImputsTemp/' . $filename . '_' . '*.*'));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // move to month/year folder

        $path = public_path('/storage/media/costsImputs/' . $yearInput);

        $usersCreated = array();

        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true);
            $path = public_path('/storage/media/costsImputs/' . $yearInput . '/' . $monthInput);
            File::makeDirectory($path, 0777, true);

            $files = glob(public_path('storage/media/costsImputsTemp/*'));

            foreach ($files as $file) {
                $filename = basename($file);
                $nif = substr($filename, 0, 9);

                // create user if it doesnt exist

                if (User::where('nif', '=', $nif)->exists()) {
                    if ($monthInput . $yearInput == substr($filename, 10, 7)) {
                        rename(public_path('storage/media/costsImputsTemp/' . $filename), public_path('storage/media/costsImputs/' . $year . '/' . $month . '/' . $filename));
                        $costsImput = new CostsImput();
                        $costsImput->user_id = Db::Table('users')->where('nif', '=', $nif)->value('id');
                        $costsImput->filename = $path . '/' . $filename;
                        $costsImput->month = $month;
                        $costsImput->year = $year;
                        $costsImput->save();
                    } else {
                        unlink(public_path('storage/media/costsImputsTemp/' . $filename));
                        $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                    }
                } else {
                    $user = new User();
                    $user->nif = $nif;
                    $user->name = "Nombre";
                    $user->email = "email@email.com";
                    $password = Str::random(10);
                    $user->password = $password;

                    $data = array(
                        'nif' => $nif,
                        'password' => $password,
                    );

                    $usersCreated[] = $data;

                    $user->save();
                    $user->assignRole('user');

                    if ($monthInput . $yearInput == substr($filename, 10, 7)) {
                        rename(public_path('storage/media/costsImputsTemp/' . $filename), public_path('storage/media/costsImputs/' . $year . '/' . $month . '/' . $filename));
                        $costsImput = new CostsImput();
                        $costsImput->user_id = Db::Table('users')->where('nif', '=', $nif)->value('id');
                        $costsImput->filename = $path . '/' . $filename;
                        $costsImput->month = $month;
                        $costsImput->year = $year;
                        $costsImput->save();
                    } else {
                        unlink(public_path('storage/media/costsImputsTemp/' . $filename));
                        $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                    }
                }
            }
        } else {
            $path = public_path('/storage/media/costsImputs/' . $year . '/' . $month);

            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);

                $files = glob(public_path('storage/media/costsImputsTemp/*'));

                foreach ($files as $file) {
                    $filename = basename($file);
                    $nif = substr($filename, 0, 9);

                    // create user if it doesnt exist

                    if (User::where('nif', '=', $nif)->exists()) {
                        if ($monthInput . $yearInput == substr($filename, 10, 7)) {
                            rename(public_path('storage/media/costsImputsTemp/' . $filename), public_path('storage/media/costsImputs/' . $year . '/' . $month . '/' . $filename));
                            $costsImput = new CostsImput();
                            $costsImput->user_id = Db::Table('users')->where('nif', '=', $nif)->value('id');
                            $costsImput->filename = $path . '/' . $filename;
                            $costsImput->month = $month;
                            $costsImput->year = $year;
                            $costsImput->save();
                        } else {
                            unlink(public_path('storage/media/costsImputsTemp/' . $filename));
                            $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                        }
                    } else {
                        $user = new User();
                        $user->nif = $nif;
                        $user->name = "Nombre";
                        $user->email = "email@email.com";
                        $password = Str::random(10);
                        $user->password = $password;

                        $data = array(
                            'nif' => $nif,
                            'password' => $password,
                        );

                        $usersCreated[] = $data;

                        $user->save();
                        $user->assignRole('user');

                        if ($monthInput . $yearInput == substr($filename, 10, 7)) {
                            rename(public_path('storage/media/costsImputsTemp/' . $filename), public_path('storage/media/costsImputs/' . $year . '/' . $month . '/' . $filename));
                            $costsImput = new CostsImput();
                            $costsImput->user_id = Db::Table('users')->where('nif', '=', $nif)->value('id');
                            $costsImput->filename = $path . '/' . $filename;
                            $costsImput->month = $month;
                            $costsImput->year = $year;
                            $costsImput->save();
                        } else {
                            unlink(public_path('storage/media/costsImputsTemp/' . $filename));
                            $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                        }
                    }
                }
            } else {

                $path = public_path('/storage/media/costsImputs/' . $year . '/' . $month);

                $files = glob(public_path('storage/media/costsImputsTemp/*'));

                foreach ($files as $file) {
                    $filename = basename($file);
                    $nif = substr($filename, 0, 9);

                    // create user if it doesnt exist

                    if (User::where('nif', '=', $nif)->exists()) {
                        if ($monthInput . $yearInput == substr($filename, 10, 7)) {
                            rename(public_path('storage/media/costsImputsTemp/' . $filename), public_path('storage/media/costsImputs/' . $year . '/' . $month . '/' . $filename));
                            $costsImput = new CostsImput();
                            $costsImput->user_id = Db::Table('users')->where('nif', '=', $nif)->value('id');
                            $costsImput->filename = $path . '/' . $filename;
                            $costsImput->month = $month;
                            $costsImput->year = $year;
                            $costsImput->save();
                        } else {
                            unlink(public_path('storage/media/costsImputsTemp/' . $filename));
                            $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                        }
                    } else {
                        $user = new User();
                        $user->nif = $nif;
                        $user->name = "Nombre";
                        $user->email = "email@email.com";
                        $password = Str::random(10);
                        $user->password = $password;

                        $data = array(
                            'nif' => $nif,
                            'password' => $password,
                        );

                        $usersCreated[] = $data;

                        $user->save();
                        $user->assignRole('user');

                        if ($monthInput . $yearInput == substr($filename, 10, 7)) {
                            rename(public_path('storage/media/costsImputsTemp/' . $filename), public_path('storage/media/costsImputs/' . $year . '/' . $month . '/' . $filename));
                            $costsImput = new CostsImput();
                            $costsImput->user_id = Db::Table('users')->where('nif', '=', $nif)->value('id');
                            $costsImput->filename = $path . '/' . $filename;
                            $costsImput->month = $month;
                            $costsImput->year = $year;
                            $costsImput->save();
                        } else {
                            unlink(public_path('storage/media/costsImputsTemp/' . $filename));
                            $uploadError[] = 'Error, mes incorrecto:' . ' ' . $filename;
                        }
                    }
                }
            }
        }


        Mail::to("raluido@gmail.com")->send(new UploadCostsImputsNotification($uploadError, $usersCreated, $monthInput, $yearInput));

        array_map('unlink', glob(public_path('storage/media/costsImputsTemp/' . '*.*')));
    }

    /**
     * The job failed to process.
     *
     * @param  Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        array_map('unlink', glob(public_path('storage/media/costsImputsTemp/' . '*.*')));

        $jobError = "Error en la carga de Imputación de Costes, vuelva a intentarlo gracias ;)";
        Mail::to("raluido@gmail.com")->send(new JobErrorNotification($jobError, $exception));
    }
}
