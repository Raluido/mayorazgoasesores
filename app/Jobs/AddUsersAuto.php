<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use setasign\Fpdi\Fpdi;
use App\Models\User;
use App\Models\Employee;
use DB;
use ZipArchive;
use Smalot\PdfParser\Parser;
use Spatie\PdfToText\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\AddUsers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AddUsersNotification;
use Illuminate\Filesystem\Filesystem;
use App\Mail\JobErrorNotification;
use Exception;


class AddUsersAuto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filename;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $filename = $this->filename;

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile(public_path('storage/media/' . $filename));
        $file = pathinfo($filename, PATHINFO_FILENAME);

        // Split each page into a new PDF
        for ($i = 1; $i <= $pageCount; $i++) {
            $newPdf = new Fpdi();
            $newPdf->addPage();
            $newPdf->setSourceFile(public_path('storage/media/' . $filename));
            $newPdf->useTemplate($newPdf->importPage($i));
            $newFilename = sprintf('%s/%s_%s.pdf', public_path('storage/media/addUsersTemp'), $file, $i);
            $newPdf->output($newFilename, 'F');
        }

        unlink(public_path('storage/media/' . $filename));

        // read each .pdf

        $files = glob(public_path('storage/media/addUsersTemp/*'));

        $usersNifNameDniAr = array();

        foreach ($files as $index) {
            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile($index);
            $content = $pdf->getText();

            $findme = 'NIF. ';
            $pos = strpos($content, $findme);
            $Nif = substr($content, ($pos + 5), 9);
            $NifFix = preg_replace('/\s+/', '', $Nif);

            $findme2 = 'EMPRESA';
            $pos2 = strpos($content, $findme2);
            $Name = substr($content, ($pos2 + 37), 33);

            $findme1 = 'D.N.I.';
            $pos1 = strpos($content, $findme1);
            $Dni = substr($content, ($pos1 + 94), 11);
            $DniFix = preg_replace('/\s+/', '', $Dni);

            // check if the nif format is correct
            $abc = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'Ñ', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            $num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $uploadError = array();

            $true = 0;

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
                    $usersNifNameDni = array();
                    $usersNifNameDni[] = $NifFix;
                    $usersNifNameDni[] = $Name;
                    $usersNifNameDni[] = $DniFix;
                    $usersNifNameDniAr[] = $usersNifNameDni;
                }
            } else {
                if (in_array($NifFix[8], $abc)) {
                    for ($i = 0; $i < 7; $i++) {
                        if (in_array($NifFix[$i], $num)) {
                            $true++;
                        } else {
                            $uploadError[] = 'El ' . $NifFix . ' ha dado error de forma, consule al administrador de sistema.';
                            break;
                        }
                    }
                    if (true == 8) {
                        $usersNifNameDni = array();
                        $usersNifNameDni[] = $NifFix;
                        $usersNifNameDni[] = $Name;
                        $usersNifNameDni[] = $DniFix;
                        $usersNifNameDniAr[] = $usersNifNameDni;
                    }
                } else {
                    $uploadError[] = 'El ' . $NifFix . ' ha dado error de forma, consule al administrador de sistema.';
                }
            }
        }

        $usersNifNameDniAr = array_map("unserialize", array_unique(array_map("serialize", $usersNifNameDniAr)));
        $usersNifPass = array();

        foreach ($usersNifNameDniAr as $index) {
            if (User::where('nif', '=', $index[0])->exists()) {
                if (Employee::where('dni', '=', $index[2])->exists()) {
                } else {
                    $employee = new Employee();
                    $userId = Db::Table('users')->where('nif', $index[0])->value('id');
                    $employee->user_id = $userId;
                    $employee->dni = $index[2];
                    $employee->save();
                }
            } else {
                $user = new User();
                $user->nif = $index[0];
                $user->name = $index[1];
                $user->email = "email@email.es";
                $password = Str::random(10);
                $user->password = $password;

                $data = array(
                    'nif' => $index[0],
                    'password' => $password,
                );

                $usersNifPass[] = $data;
                $user->save();
                $user->assignRole('user');

                if (Employee::where('dni', '=', $index[2])->exists()) {
                } else {

                    $employee = new Employee();
                    $userId = Db::Table('users')->where('nif', $index[0])->value('id');
                    $employee->user_id = $userId;
                    $employee->dni = $index[2];
                    $employee->save();
                }
            }
        }

        Mail::to("raluido@gmail.com")->send(new AddUsersNotification($usersNifPass, $uploadError));

        $files = glob(public_path('storage/media/addUsersTemp/*'));

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
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
        array_map('unlink', glob(public_path('storage/media/addUsersTemp/' . '*.*')));

        $jobError = "Error en la creación de empresas, vuelva a intentarlo gracias ;)";
        Mail::to("raluido@gmail.com")->send(new JobErrorNotification($jobError, $exception));
    }
}
