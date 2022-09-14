<?php

namespace App\Http\Controllers;

use Smalot\PdfParser\Parser;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
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

        return view('costsimputs.uploadForm')->with('successMsg', "Los documentos de imputación de costes han comenzado a subirse, tardaremos unos minutos, gracias ;)");
    }

    public function downloadForm()
    {
        $month = null;
        $year = null;
        $nif = null;

        return view('costsimputs.downloadForm')->with('month', $month)->with('year', $year)->with('nif', $nif);
    }

    public function getData(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $nif = $request->input('nif');

        return view('costsimputs.downloadForm', compact('month', 'year', 'nif'));
    }


    public function downloadCostsImputs($month, $year, $nif)
    {

        $files = DB::Table('users')
            ->join('costs_imputs', 'costs_imputs.user_id', '=', 'users.id')
            ->where('users.nif', '=', $nif)
            ->where('costs_imputs.year', '=', $year)
            ->where('costs_imputs.month', '=', $month)
            ->select('costs_imputs.filename')
            ->get()
            ->toArray();

        if ($files != null) {

            $zipFilename = $nif . $month . $year . '.zip';
            $zip = new ZipArchive;

            $public_dir = public_path('storage/media/costsimputs/' . $year . '/' . $month);

            if ($zip->open($public_dir . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                foreach ($files as $file) {
                    $filename = basename((array_values((array)$file))[0]);
                    $temp = (array_values((array)$filename))[0];
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

        return view('costsimputs.downloadForm')->with('month', $month)->with('year', $year)->with('nif', $nif);
    }

    public function downloadAllCostsImputs($month, $year)
    {
        $nif = null;

        $files = DB::Table('users')
            ->join('costs_imputs', 'costs_imputs.user_id', '=', 'users.id')
            ->where('costs_imputs.year', '=', $year)
            ->where('costs_imputs.month', '=', $month)
            ->select('costs_imputs.filename')
            ->get()
            ->toArray();

        if ($files != null) {

            $zipFilename = $month . $year . '.zip';
            $zip = new ZipArchive;

            $public_dir = public_path('storage/media/costsimputs/' . $year . '/' . $month);

            if ($zip->open($public_dir . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                foreach ($files as $file) {
                    $filename = basename((array_values((array)$file))[0]);
                    $temp = (array_values((array)$filename))[0];
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

        return view('costsimputs.downloadForm')->with('month', $month)->with('year', $year)->with('nif', $nif);
    }

    public function showForm()
    {
        return view('costsimputs.showForm');
    }

    public function showCostsImputs(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $costsimputs = DB::Table('users')
            ->join('costs_imputs', 'costs_imputs.user_id', '=', 'users.id')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->select('users.nif', 'costs_imputs.month', 'costs_imputs.year', 'costs_imputs.user_id')
            ->groupBy('users.nif')
            ->paginate(10);

        if ($costsimputs[0] == null) {
            echo '<div class="alert alert-warning">Aún no están disponibles las imputaciones de costes de ' . $month . $year . '<div>';
        }

        return view('costsimputs.showCostsImputs', compact('costsimputs', 'month', 'year'));
    }


    public function deleteCostsImputs($id, $year, $month)
    {
        $check = DB::Table('costs_imputs')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('user_id', '=', $id)
            ->count();

        $costsimputId = DB::Table('costs_imputs')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('user_id', '=', $id)
            ->select('filename')
            ->get()
            ->toArray();


        foreach ($costsimputId as $index) {
            unlink((array_values((array)$index))[0]);
        }

        DB::Table('costs_imputs')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('user_id', '=', $id)
            ->delete();

        return view('costsimputs.showForm');
    }

    public function deleteAllCostsImputs()
    {
        File::deleteDirectory(public_path('/storage/media/costsImputs'));
        $path = public_path('/storage/media/costsImputs');
        File::makeDirectory($path, 0777, true);
        DB::table('costs_imputs')->delete();

        return view('costsimputs.showForm');
    }
}
