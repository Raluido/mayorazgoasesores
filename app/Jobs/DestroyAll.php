<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;
use ZipArchive;
use Smalot\PdfParser\Parser;
use Spatie\PdfToText\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeleteNotification;
use App\Mail\JobErrorNotification;
use Exception;
use Illuminate\Support\Facades\Storage;


class DestroyAll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $path = 'storage/media/payrolls';

        if (Storage::exists($path)) {
            $delete = Storage::deleteDirectory($path);
            if ($delete) {
                Storage::makeDirectory($path, 0777, true);
                $payrolls = Db::Table('payrolls')
                    ->delete();
                $employees = Db::Table('employees')
                    ->delete();
            }
        }

        $path = 'storage/media/costsImputs';

        if (Storage::exists($path)) {
            $delete = Storage::deleteDirectory($path);
            if ($delete) {
                Storage::makeDirectory($path, 0777, true);
                $costsImputs = Db::Table('costs_imputs')
                    ->delete();
            }
        }

        $path = 'storage/media/othersDocuments';

        if (Storage::exists($path)) {
            $delete = Storage::deleteDirectory($path);
            if ($delete) {
                Storage::makeDirectory($path, 0777, true);
                $othersDocuments = Db::Table('others_documents')
                    ->delete();
            }
        }

        if ($payrolls >= 0 && $employees >= 0 && $costsImputs >= 0 && $othersDocuments >= 0) {
            $users = Db::Table('users')
                ->where('id', '>', '2')
                ->delete();
            if ($users) {
                $passed = "El proceso de eliminación de toda la base de datos ha finalizado con éxito";
            } else {
                $passed = "El proceso de eliminación de toda la base de datos ha fallado";
            }

            Mail::to(ENV('MAIL_TO_ADDRESS'))->send(new DeleteNotification($passed));
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
        $jobError = "Error en la eliminando TODOS los datos, vuelva a intentarlo gracias ;)";
        Mail::to(ENV('MAIL_TO_ADDRESS'))->send(new JobErrorNotification($jobError, $exception));
    }
}
