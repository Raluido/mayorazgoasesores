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
use App\Mail\DeleteNotification;
use Illuminate\Filesystem\Filesystem;
use App\Mail\JobErrorNotification;
use Exception;
use Illuminate\Support\Facades\File;


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
        File::deleteDirectory(public_path('/storage/media/payrolls'));
        $path = public_path('/storage/media/payrolls');
        File::makeDirectory($path, 0775, true);
        Db::Table('payrolls')->delete();

        Db::Table('employees')->delete();

        File::deleteDirectory(public_path('/storage/media/costsImputs'));
        $path = public_path('/storage/media/costsImputs');
        File::makeDirectory($path, 0775, true);
        Db::Table('costs_imputs')->delete();

        File::deleteDirectory(public_path('/storage/media/othersDocuments'));
        $path = public_path('/storage/media/othersDocuments');
        File::makeDirectory($path, 0775, true);
        Db::Table('others_documents')->delete();

        Db::Table('users')->where('id', '>', '2')->delete();

        $passed = "El proceso de eliminación de toda la base de datos ha finalizado con éxito";


        Mail::to(ENV('MAIL_TO_ADDRESS'))->send(new DeleteNotification($passed));
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
