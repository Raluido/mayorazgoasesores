<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobErrorNotification;
use Illuminate\Support\Facades\File;
use Exception;


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
        File::deleteDirectory(public_path('/storage/media/payrolls'));
        $path = public_path('/storage/media/payrolls');
        File::makeDirectory($path, 0777, true);
        Db::Table('payrolls')->delete();

        Db::Table('employees')->delete();
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
        Mail::to("raluido@gmail.com")->send(new JobErrorNotification($jobError, $exception));
    }
}
