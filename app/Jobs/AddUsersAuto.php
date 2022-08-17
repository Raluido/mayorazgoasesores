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
use App\Mail\ContactMails;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class AddUsersAuto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filenamewithextension;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filenamewithextension)
    {
        $this->filenamewithextension = $filenamewithextension;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $filenamewithextension = $this->filenamewithextension;

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


        // read each .pdf

        $fileNameNoExt = pathinfo($filenamewithextension, PATHINFO_FILENAME);

        $usersNifNameAr = array();

        for ($i = 1; $i <= $pageCount; $i++) {
            $path = public_path('storage/media/temp/' . $fileNameNoExt . '_' . $i . '.pdf');
            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile($path);
            $content = $pdf->getText();

            $findme = 'NIF. ';
            $pos = strpos($content, $findme);
            $Nif = substr($content, ($pos + 5), 9);
            $NifFix = preg_replace('/\s+/', '', $Nif);

            $findme2 = 'EMPRESA';
            $pos2 = strpos($content, $findme2);
            $Name = substr($content, ($pos2 + 37), 31);

            $usersNifName = array();
            $usersNifName[] = $NifFix;
            $usersNifName[] = $Name;
            $usersNifNameAr[] = $usersNifName;
        }

        $usersNifNameAr = array_map("unserialize", array_unique(array_map("serialize", $usersNifNameAr)));
        $usersNifPass = array();

        foreach ($usersNifNameAr as $index) {

            if (User::where('nif', '=', $index[0])->exists()) {
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
                $user->assignRole(2);
            }
        }

        Mail::to("raluido@gmail.com")->send(new ContactMails($usersNifPass));

        unlink(public_path('storage/media/' . $filenamewithextension));

        $files = glob(public_path('storage/media/temp/*'));

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
