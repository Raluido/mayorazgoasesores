<?php

namespace App\Http\Controllers;

use Smalot\PdfParser\Parser;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Fpdi;
use App\Models\CostsImput;
use DB;
use ZipArchive;
use Illuminate\Support\Facades\Auth;
use App\Jobs\UploadCostsImputs;

class CostsImputsController extends Controller
{

    public function uploadForm()
    {
        return view('costsimputs.uploadForm');
    }

    public function uploadCostsImputs(Request $request)
    {
        $file = $request->file('costsimputs');

        if ($request->hasFile('costsimputs')) {
            $allowedfileExtension = ['pdf'];
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);
            if ($check) {
                $filenamewithextension = "addCostsImputs.pdf";
                $file->storeAs('storage/media/',  $filenamewithextension);
                $month = $request->input('month');
                $year = $request->input('year');
                UploadCostsImputs::dispatch($filenamewithextension, $month, $year);
            } else {
                echo '<div class="alert alert-warning"><strong>Warning!</strong> Sólo se admiten archivos con extensión .pdf</div>';

                return view('costsimputs.uploadForm');
            }
        } else {
            echo '<div class="alert alert-warning"><strong>Warning!</strong> No has añadido ningun archivo aún.</div>';

            return view('costsimputs.uploadForm');
        }

        return view('costsimputs.uploadForm')->with('successMsg', "Los documentos de imputacón de costes han comenzado a subirse, tardaremos unos minutos, gracias ;)");
    }

    public function downloadForm()
    {
        $month = null;
        $year = null;

        return view('costsimputs.downloadForm')->with('month', $month)->with('year', $year);
    }

    public function getData(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $year = $request->input('nif');

        return view('costsimputs.downloadForm', compact('month', 'year', 'nif'));
    }


    public function downloadCostsImputs($month, $year, $nif)
    {
        if ($month || $year != null) {

            $files = DB::Table('costsimputs')->where('year', $year)->where('month', $month)->where('nif', $nif)->select('filename')->get()->toArray();

            if ($files != null) {

                $zipFilename = $nif . $month . $year . '.zip';
                $zip = new ZipArchive;

                $public_dir = public_path('storage/media/costsimputs/' . $year . '/' . $month);

                if ($zip->open($public_dir . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                    foreach ($files as $file) {
                        $temp = (array_values((array)$file))[0];
                        $zip->addFile($public_dir . '/' . $temp, $temp);
                    }
                    $zip->close();
                }

                if (file_exists($public_dir . '/' . $zipFilename)) {
                    return response()->download(public_path('storage/media/costsimputs/' . $year . '/' . $month . '/' . $zipFilename))->deleteFileAfterSend(true);
                }
            } else {
                echo '<div class="alert alert-warning"><strong>Warning!</strong> Las modelos de imputación de costes de ' . $month . $year . ' no están disponibles.<div>';
            }
        } else {
            echo '<div class="alert alert-warning"><strong>Warning!</strong> Debes elegir un mes y un año.<div>';
        }

        return view('costsimputs.downloadForm')->with('month', $month)->with('year', $year);
    }

    public function downloadAllCostsImputs($month, $year)
    {
        if ($month || $year != null) {

            $files = DB::Table('costs_imputs')->where('year', $year)->where('month', $month)->select('filename')->get()->toArray();

            if ($files != null) {

                $zipFilename = $month . $year . '.zip';
                $zip = new ZipArchive;

                $public_dir = public_path('storage/media/costsimputs/' . $year . '/' . $month);

                if ($zip->open($public_dir . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                    foreach ($files as $file) {
                        $temp = (array_values((array)$file))[0];
                        $zip->addFile($public_dir . '/' . $temp, $temp);
                    }
                    $zip->close();
                }

                if (file_exists($public_dir . '/' . $zipFilename)) {
                    return response()->download(public_path('storage/media/costsimputs/' . $year . '/' . $month . '/' . $zipFilename))->deleteFileAfterSend(true);
                }
            } else {
                echo '<div class="alert alert-warning"><strong>Warning!</strong> Los modelos de imputación de costes de ' . $month . $year . ' no están disponibles.<div>';
            }
        } else {
            echo '<div class="alert alert-warning"><strong>Warning!</strong> Debes elegir un mes y un año.<div>';
        }

        return view('costsimputs.downloadForm')->with('month', $month)->with('year', $year);
    }

    public function showForm()
    {
        return view('costsimputs.showForm');
    }

    public function showCostsImputs(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $costsimputs = DB::Table('costs_imputs')->where('year', $year)->where('month', $month)->get()->toArray();

        return view('costsimputs.showCostsImputs', compact('costsimputs', 'month', 'year'));
    }

    public function deleteCostsImputs(CostsImput $costsimput, $month, $year)
    {
        $costsimput->delete();

        $costsimputs = DB::Table('costs_imputs')->where('year', $year)->where('month', $month)->get()->toArray();
        unlink(public_path('/storage/media/costsImputs/' . $year . '/' . $month . '/' . $costsimput->filename));

        return view('costsimputs.showForm', compact('costsimputs'));
    }

    public function deleteAllCostsImputs($month, $year)
    {

        DB::table('costs_imputs')->where('year', $year)->where('month', $month)->delete();

        File::deleteDirectory(public_path('/storage/media/costsImputs/' . $year . '/' . $month));

        return view('costsimputs.showForm');
    }
}
