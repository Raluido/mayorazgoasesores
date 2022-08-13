<?php

namespace App\Http\Controllers;

use Smalot\PdfParser\Parser;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Fpdi;
use App\Models\Payroll;
use App\Models\User;
use DB;
use ZipArchive;
use Illuminate\Support\Facades\Auth;
use Payrolls;
use App\Jobs\UploadPayrolls;

class PayrollsController extends Controller
{

    public function uploadForm()
    {
        return view('payrolls.uploadForm');
    }

    public function uploadPayrolls(Request $request)
    {
        $file = $request->file('payrolls');

        if ($request->hasFile('payrolls')) {
            $allowedfileExtension = ['pdf'];
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);
            if ($check) {
                $filenamewithextension = $file->getClientOriginalName();
                $file->storeAs('storage/media/temp/',  $filenamewithextension);

                $month = $request->input('month');
                $year = $request->input('year');

                UploadPayrolls::dispatch($filenamewithextension, $month, $year);
            } else {
                echo '<div class="alert alert-warning"><strong>Warning!</strong> Sólo se admiten archivos con extensión .pdf</div>';

                return view('payrolls.uploadForm');
            }
        } else {
            echo '<div class="alert alert-warning"><strong>Warning!</strong> No has añadido ningun archivo aún.</div>';

            return view('payrolls.uploadForm');
        }

        return view('payrolls.uploadForm')->with('successMsg', "Las nóminas han comenzado a subirse, tardaremos unos minutos, gracias ;)");
    }

    public function downloadForm()
    {
        $month = null;
        $year = null;

        return view('payrolls.downloadForm')->with('month', $month)->with('year', $year);
    }

    public function getData(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        return view('payrolls.downloadForm', compact('month', 'year'));
    }


    public function downloadPayrolls(Request $request, $month, $year)
    {
        if ($month || $year != null) {

            $files = DB::Table('payrolls')->where('monthyear', $month . $year)->where('nif', Auth::user()->nif)->select('filename')->get()->toArray();

            if ($files != null) {

                $zipFilename = Auth::user()->nif . $month . $year . '.zip';
                $zip = new ZipArchive;

                $public_dir = public_path('storage/media/' . $month . $year);

                if ($zip->open($public_dir . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                    foreach ($files as $file) {
                        $temp = (array_values((array)$file))[0];
                        $zip->addFile($public_dir . '/' . $temp, $temp);
                    }
                    $zip->close();
                }

                if (file_exists($public_dir . '/' . $zipFilename)) {
                    return response()->download(public_path('storage/media/' . $month . $year . '/' . $zipFilename))->deleteFileAfterSend(true);
                }
            } else {
                echo '<div class="alert alert-warning"><strong>Warning!</strong> Las nóminas de ' . $month . $year . ' no están disponibles.<div>';
            }
        } else {
            echo '<div class="alert alert-warning"><strong>Warning!</strong> Debes elegir un mes y un año.<div>';
        }

        return view('payrolls.downloadForm')->with('month', $month)->with('year', $year);
    }
}
