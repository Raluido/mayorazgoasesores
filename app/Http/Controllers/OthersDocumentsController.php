<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
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
        $userid = DB::Table('users')->where('nif', $nif)->value('id');

        if (User::where('nif', $nif)->exists()) {

            if ($request->hasFile('othersdocuments')) {

                $path = public_path('/storage/media/othersDocuments/' . $year);

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0777, true);
                    $path = public_path('/storage/media/othersDocuments/' . $year . '/' . $month);
                    File::makeDirectory($path, 0777, true);
                    $path = public_path('/storage/media/othersDocuments/' . $year . '/' . $month . '/' . $nif);
                    File::makeDirectory($path, 0777, true);

                    $files = $request->file('othersdocuments');

                    foreach ($files as $index) {
                        $name = $index->getClientOriginalName();
                        $index->storeAs('storage/media/othersDocuments/' . $year . '/' . $month . '/' . $nif, $name);
                        $otherDocument = new OtherDocument();
                        $otherDocument->user_id = $userid;
                        $otherDocument->filename = $path . '/' . $name;
                        $otherDocument->month = $month;
                        $otherDocument->year = $year;
                        $otherDocument->save();
                    }
                } else {
                    $path = public_path('/storage/media/othersDocuments/' . $year . '/' . $month);
                    if (!File::exists($path)) {
                        File::makeDirectory($path, 0777, true);
                        $path = public_path('/storage/media/othersDocuments/' . $year . '/' . $month . '/' . $nif);
                        File::makeDirectory($path, 0777, true);

                        $files = $request->file('othersdocuments');

                        foreach ($files as $index) {
                            $name = $index->getClientOriginalName();
                            $index->storeAs('storage/media/othersDocuments/' . $year . '/' . $month . '/' . $nif, $name);
                            $otherDocument = new OtherDocument();
                            $otherDocument->user_id = $userid;
                            $otherDocument->filename = $path . '/' . $name;
                            $otherDocument->month = $month;
                            $otherDocument->year = $year;
                            $otherDocument->save();
                        }
                    } else {
                        $path = public_path('/storage/media/othersDocuments/' . $year . '/' . $month . '/' . $nif);
                        if (!File::exists($path)) {
                            File::makeDirectory($path, 0777, true);
                            $files = $request->file('othersdocuments');

                            foreach ($files as $index) {
                                $name = $index->getClientOriginalName();
                                $index->storeAs('storage/media/othersDocuments/' . $year . '/' . $month . '/' . $nif, $name);
                                $otherDocument = new OtherDocument();
                                $otherDocument->user_id = $userid;
                                $otherDocument->filename = $path . '/' . $name;
                                $otherDocument->month = $month;
                                $otherDocument->year = $year;
                                $otherDocument->save();
                            }
                        } else {

                            $path = public_path('/storage/media/othersDocuments/' . $year . '/' . $month . '/' . $nif);

                            $files = $request->file('othersdocuments');

                            foreach ($files as $index) {
                                $check = DB::Table('users')
                                    ->join('others_documents', 'others_documents.user_id', '=', 'users.id')
                                    ->where('others_documents.year', '=', $year)
                                    ->where('others_documents.month', '=', $month)
                                    ->where('users.nif', '=', $nif)
                                    ->select('others_documents.filename')
                                    ->exists();

                                if ($check) {
                                    $name = $index->getClientOriginalName();
                                    OtherDocument::where('filename', $name)->delete();
                                    $index->storeAs('storage/media/othersDocuments/' . $year . '/' . $month . '/' . $nif, $name);
                                    $otherDocument = new OtherDocument();
                                    $otherDocument->user_id = $userid;
                                    $otherDocument->filename = $path . '/' . $name;
                                    $otherDocument->month = $month;
                                    $otherDocument->year = $year;
                                    $otherDocument->save();
                                } else {
                                    $name = $index->getClientOriginalName();
                                    $index->storeAs('storage/media/othersDocuments/' . $year . '/' . $month . '/' . $nif, $name);
                                    $otherDocument = new OtherDocument();
                                    $otherDocument->user_id = $userid;
                                    $otherDocument->filename = $path . '/' . $name;
                                    $otherDocument->month = $month;
                                    $otherDocument->year = $year;
                                    $otherDocument->save();
                                }
                            }
                        }
                    }
                }
            } else {
                echo '<div class="alert alert-warning"><strong>Warning!</strong> No has añadido ningun archivo aún.</div>';

                return view('othersdocuments.uploadForm');
            }

            return view('othersdocuments.uploadForm')->with('successMsg', "Los documentos de imputación de costes se han subido correctamente, gracias ;)");
        } else {
            echo '<div class="alert alert-warning"><strong>Warning!</strong>El ' . $nif . ' corresponde a una empresa que no ha sido creada aún.</div>';
        }
    }

    public function downloadForm()
    {
        return view('othersdocuments.downloadForm');
    }

    public function downloadList(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $othersdocuments = DB::Table('users')
            ->join('others_documents', 'others_documents.user_id', '=', 'users.id')
            ->where('others_documents.year', '=', $year)
            ->where('others_documents.month', '=', $month)
            ->where('users.nif', '=', Auth::user()->nif)
            ->get()
            ->toArray();

        return view('othersdocuments.downloadList', compact('othersdocuments', 'month', 'year'));
    }

    public function downloadOthersDocuments(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $request->validate([
            'othersDocuments' => 'required|min:1'
        ]);

        $othersDocuments = DB::Table('users')
            ->join('others_documents', 'others_documents.user_id', '=', 'users.id')
            ->where('others_documents.year', '=', $year)
            ->where('others_documents.month', '=', $month)
            ->where('users.nif', '=', Auth::user()->nif)
            ->select('others_documents.filename')
            ->get()
            ->toArray();

        if ($othersDocuments != null) {

            if ($month || $year != null) {

                $zipFilename = Auth::user()->nif . '_' . $month . $year . '.zip';
                $zip = new ZipArchive;

                $public_dir = public_path('storage/media/othersDocuments/' . $year . '/' . $month . '/' . Auth::user()->nif);

                if ($zip->open($public_dir . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                    foreach ($othersDocuments as $index) {
                        $filename = basename((array_values((array)$index))[0]);
                        $temp = (array_values((array)$filename))[0];
                        $zip->addFile($public_dir . '/' . $temp, $temp);
                    }
                    $zip->close();
                }

                if (file_exists($public_dir . '/' . $zipFilename)) {
                    return response()->download(public_path('storage/media/othersDocuments/' . $year . '/' . $month . '/' . Auth::user()->nif . '/' . $zipFilename))->deleteFileAfterSend(true);
                }
            } else {
                echo '<div class="alert alert-warning"><strong>Warning!</strong> Debes elegir un mes y un año.<div>';
            }
        } else {
            echo '<div class="alert alert-warning"><strong>Warning!</strong> Hemos detectado un error, vuelva a intentarlo, gracias ;)<div>';
        }

        $othersDocuments = DB::Table('users')
            ->join('others_documents', 'others_documents.user_id', '=', 'users.id')
            ->where('others_documents.year', '=', $year)
            ->where('others_documents.month', '=', $month)
            ->where('users.nif', '=', Auth::user()->nif)
            ->get()
            ->toArray();

        if ($othersDocuments != null) {
            return view('othersdocuments.downloadList', compact('othersdocuments', 'month', 'year'));
        } else {
            return view('othersdocuments.downloadList', compact('No hay documentos en éstas fechas.'));
        }

        return view('othersdocuments.downloadList', compact('othersdocuments', 'month', 'year'));
    }

    public function showForm()
    {
        return view('othersdocuments.showForm');
    }

    public function showOthersDocuments(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $othersdocuments = DB::Table('users')
            ->join('others_documents', 'others_documents.user_id', '=', 'users.id')
            ->where('others_documents.year', '=', $year)
            ->where('others_documents.month', '=', $month)
            ->select('users.nif', 'others_documents.id', 'others_documents.filename', 'others_documents.year', 'others_documents.month')
            ->paginate(10);

        if ($othersdocuments[0] != null) {
            return view('othersdocuments.showOtherDocuments', compact('othersdocuments', 'month', 'year'));
        } else {
            echo '<div class="alert alert-warning">No hay documentos en ' . $month . $year . '<div>';
        }
    }

    public function deleteOthersDocuments($id)
    {
        $otherdocumentId = DB::Table('others_documents')->where('id', '=', $id)->value('filename');
        unlink($otherdocumentId);

        DB::Table('others_documents')->where('id', '=', $id)->delete();

        return view('othersdocuments.showForm');
    }

    public function deleteAllOtherDocuments()
    {

        File::deleteDirectory(public_path('/storage/media/othersDocuments'));
        $path = public_path('/storage/media/othersDocuments');
        File::makeDirectory($path, 0777, true);
        DB::table('others_documents')->delete();

        return view('othersdocuments.showForm');
    }
}
