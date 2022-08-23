<?php

namespace App\Http\Controllers;

use Smalot\PdfParser\Parser;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Fpdi;
use App\Models\OtherDocument;
use App\Models\User;
use DB;
use ZipArchive;
use Illuminate\Support\Facades\Auth;

class OthersDocumentsController extends Controller
{

    public function uploadForm()
    {
        return view('othersdocuments.uploadForm');
    }

    public function uploadOthersDocuments(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $nif = $request->input('nif');

        if ($request->hasFile('othersdocuments')) {

            $docs = [];
            $files = $request->file('othersdocuments');

            foreach ($files as $key => $file) {
                $name = $file->getClientOriginalName();
                $file->storeAs('public/media/othersdocuments' . '/' . $year . '/' . $month, $name);
                $docs[] = new OtherDocument([
                    'filename' => $name,
                ]);

                $otherDocument = new OtherDocument();
                $otherDocument->nif = $nif;
                $otherDocument->filename = $name;
                $otherDocument->month = $month;
                $otherDocument->year = $year;
                $otherDocument->save();
            }
        } else {
            echo '<div class="alert alert-warning"><strong>Warning!</strong> No has añadido ningun archivo aún.</div>';

            return view('othersdocuments.uploadForm');
        }

        return view('othersdocuments.uploadForm')->with('successMsg', "Los documentos de imputacón de costes se han subido, gracias ;)");
    }


    public function downloadForm()
    {
        $month = null;
        $year = null;

        return view('othersdocuments.downloadForm')->with('month', $month)->with('year', $year);
    }

    public function getData(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        return view('othersdocuments.downloadForm', compact('month', 'year'));
    }


    public function downloadOthersDocuments($month, $year)
    {
        if ($month || $year != null) {

            $files = DB::Table('othersdocuments')->where('year', $year)->where('month', $month)->where('nif', Auth::user()->nif)->select('filename')->get()->toArray();

            if ($files != null) {

                $zipFilename = Auth::user()->nif . '_' . $month . $year . '.zip';
                $zip = new ZipArchive;

                $public_dir = public_path('storage/media/othersdocuments/' . $year . '/' . $month);

                if ($zip->open($public_dir . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                    foreach ($files as $file) {
                        $temp = (array_values((array)$file))[0];
                        $zip->addFile($public_dir . '/' . $temp, $temp);
                    }
                    $zip->close();
                }

                if (file_exists($public_dir . '/' . $zipFilename)) {
                    return response()->download(public_path('storage/media/othersdocuments/' . $year . '/' . $month . '/' . $zipFilename))->deleteFileAfterSend(true);
                }
            } else {
                echo '<div class="alert alert-warning"><strong>Warning!</strong> Los documentos solicitados de ' . $month . $year . ' no están disponibles.<div>';
            }
        } else {
            echo '<div class="alert alert-warning"><strong>Warning!</strong> Debes elegir un mes y un año.<div>';
        }

        return view('otherdocuments.downloadForm')->with('month', $month)->with('year', $year);
    }

    public function showForm()
    {
        return view('othersdocuments.showForm');
    }

    public function showOthersDocuments(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $othersdocuments = DB::Table('otherdocuments')->where('year', $year)->where('month', $month)->get()->toArray();

        return view('othersdocuments.showOtherDocuments', compact('othersdocuments', 'month', 'year'));
    }

    public function deleteOthersDocuments(OtherDocument $otherdocument, $month, $year)
    {
        $otherdocument->delete();

        $month = substr(0, 3);
        $year = substr(3, 2);

        $otherdocument = DB::Table('othersdocuments')->where('year', $year)->where('month', $month)->get()->toArray();

        return view('othersdocuments.showForm');
    }

    public function deleteAllOtherDocuments($month, $year)
    {

        OtherDocument::where('year', $year)::where('month', $month)->delete();

        File::deleteDirectory(public_path('/storage/media/othersDocuments/' . $year . '/' . $month));

        return view('othersdocuments.showForm');
    }
}
