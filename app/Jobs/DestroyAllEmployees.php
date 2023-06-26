<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobErrorNotification;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Mail\DeleteNotification;


class DestroyAllEmployees implements ShouldQueue
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
                Storage::makeDirectory($path, 0775, true);
                $delete = Db::Table('payrolls')
                    ->delete();
                if ($delete) {
                    $employees = Db::Table('employees')
                        ->delete();
                    if ($employees) {
                        $passed = "El proceso de eliminación de todos los trabajadores ha finalizado con éxito";
                    } else {
                        $passed = "El proceso de eliminación de todos los trabajadores ha fallado";
                    }
                } else {
                    $passed = "El proceso de eliminación de todos los trabajadores ha fallado";
                }
            } else {
                $passed = "El proceso de eliminación de todos los trabajadores ha fallado";
            }
        }


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
        $jobError = "Error en la eliminando a TODOS los empleados, vuelva a intentarlo gracias ;)";
        Mail::to(ENV('MAIL_TO_ADDRESS'))->send(new JobErrorNotification($jobError, $exception));
    }
}
