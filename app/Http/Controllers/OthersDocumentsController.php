<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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
        $presentYear = date("Y");

        return view('othersdocuments.uploadForm')
            ->with('presentYear', $presentYear);
    }

    public function uploadOthersDocuments(Request $request)
    {
        $presentYear = date("Y");

        $month = $request->input('month');
        $year = $request->input('year');
        $nif = $request->input('nif');
        $user = DB::Table('users')
            ->select('id')
            ->where('nif', $nif)
            ->get();

        if (count($user) > 0) {

            if ($request->hasFile('othersdocuments')) {

                $path = 'storage/media/othersDocuments/' . $year;

                if (!Storage::exists($path)) {
                    Storage::makeDirectory($path, 0775, true);
                    $path = 'storage/media/othersDocuments/' . $year . '/' . $month;
                    Storage::makeDirectory($path, 0775, true);
                    $path = 'storage/media/othersDocuments/' . $year . '/' . $month . '/' . $nif;
                    Storage::makeDirectory($path, 0775, true);
                } else {
                    $path = 'storage/media/othersDocuments/' . $year . '/' . $month;
                    if (!Storage::exists($path)) {
                        Storage::makeDirectory($path, 0775, true);
                        $path = 'storage/media/othersDocuments/' . $year . '/' . $month . '/' . $nif;
                        Storage::makeDirectory($path, 0775, true);
                    } else {
                        $path = 'storage/media/othersDocuments/' . $year . '/' . $month . '/' . $nif;
                        if (!Storage::exists($path)) {
                            Storage::makeDirectory($path, 0775, true);
                        }
                    }
                }

                $files = $request->file('othersdocuments');

                foreach ($files as $index) {
                    $filename = $index->getClientOriginalName();
                    $othersDocuments = DB::Table('users')
                        ->join('others_documents', 'others_documents.user_id', '=', 'users.id')
                        ->where('others_documents.year', '=', $year)
                        ->where('others_documents.month', '=', $month)
                        ->where('others_documents.filename', '=', $filename)
                        ->where('users.nif', '=', $nif)
                        ->get();

                    if (count($othersDocuments) > 0) {
                        if (Storage::exists($path . '/' . $filename)) {
                            $delete = Storage::delete($path . '/' . $filename);
                            if ($delete) {
                                OtherDocument::where('filename', $filename)
                                    ->delete();
                            }
                        }

                        $index->storeAs($path . '/', $filename);
                        $otherDocument = new OtherDocument();
                        $otherDocument->user_id = $user[0]->id;
                        $otherDocument->filename = $path . '/' . $filename;
                        $otherDocument->month = $month;
                        $otherDocument->year = $year;
                        $otherDocument->save();
                    } else {
                        $name = $index->getClientOriginalName();
                        $index->storeAs($path . '/', $name);
                        $otherDocument = new OtherDocument();
                        $otherDocument->user_id = $user[0]->id;
                        $otherDocument->filename = $path . '/' . $name;
                        $otherDocument->month = $month;
                        $otherDocument->year = $year;
                        $otherDocument->save();
                    }
                }
            } else {
                return redirect()
                    ->route('othersdocuments.uploadForm')
                    ->with('presentYear', $presentYear)
                    ->withErrors(__('No has añadido ningun archivo aún.'));
            }
            return redirect()
                ->route('othersdocuments.uploadForm')
                ->with('presentYear', $presentYear)
                ->withSuccess(__('Los documentos se han subido correctamente ;)'));
        } else {
            return redirect()
                ->route('othersdocuments.uploadForm')
                ->with('presentYear', $presentYear)
                ->withErrors(__('El ' . $nif . ' corresponde a una empresa que no ha sido creada aún.'));
        }
    }

    public function downloadForm()
    {
        $presentYear = date("Y");
        return view('othersdocuments.downloadForm')
            ->with('presentYear', $presentYear);
    }

    public function downloadList(Request $request)
    {
        $presentYear = date("Y");
        $month = $request->input('month');
        $year = $request->input('year');

        $othersdocuments = DB::Table('users')
            ->join('others_documents', 'others_documents.user_id', '=', 'users.id')
            ->where('others_documents.year', '=', $year)
            ->where('others_documents.month', '=', $month)
            ->where('users.nif', '=', Auth::user()->nif)
            ->get();

        if (count($othersdocuments) == 0) {
            return redirect()
                ->route('othersdocuments.downloadForm')
                ->with('presentYear', $presentYear)
                ->withErrors(__('Los documentos de ' . $month . $year . ' no están disponibles aún.'));
        }
        return view('othersdocuments.downloadList', compact('othersdocuments', 'month', 'year'));
    }

    public function downloadOthersDocuments(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $otherDocumentSlc = $request->othersDocuments;


        if (!isset($otherDocumentSlc)) {

            $othersdocuments = DB::Table('users')
                ->join('others_documents', 'others_documents.user_id', '=', 'users.id')
                ->where('others_documents.year', '=', $year)
                ->where('others_documents.month', '=', $month)
                ->where('users.nif', '=', Auth::user()->nif)
                ->get()
                ->toArray();

            return redirect()
                ->route('othersdocuments.downloadList', compact('othersdocuments', 'month', 'year'))
                ->withErrors(__('Debe seleccionar que archivos quiere descargar'));
        } else {

            $zipFilename = Auth::user()->nif . '_' . $month . $year . '.zip';
            $zip = new ZipArchive;

            $publicDir = public_path('storage/media/othersDocuments/' . $year . '/' . $month . '/' . Auth::user()->nif);
            $tempFolder = public_path('storage/media/othersDocuments');

            if ($zip->open($tempFolder . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                foreach ($otherDocumentSlc as $index) {
                    $zip->addFile($index, basename($index));
                }
                $zip->close();
            }

            if (file_exists($tempFolder . '/' . $zipFilename)) {
                return response()->download($tempFolder . '/' . $zipFilename)->deleteFileAfterSend(true);
            } else {
                return redirect()
                    ->route('othersdocuments.downloadForm')
                    ->withErrors(__('Hemos detectado un error, vuelva a intentarlo, gracias ;)'));
            }
        }
    }

    public function showForm()
    {
        $presentYear = date("Y");

        return view('othersdocuments.showForm')
            ->with('presentYear', $presentYear);
    }

    public function showOthersDocuments(Request $request, $month = null, $year = null)
    {
        $presentYear = date("Y");

        if ($year == null && $month == null) {
            $month = $request->input('month');
            $year = $request->input('year');
        }

        $othersdocuments = DB::Table('users')
            ->join('others_documents', 'others_documents.user_id', '=', 'users.id')
            ->where('others_documents.year', '=', $year)
            ->where('others_documents.month', '=', $month)
            ->select('users.nif', 'others_documents.id', 'others_documents.filename', 'others_documents.year', 'others_documents.month')
            ->paginate(10);

        $othersdocuments->setPath('/othersdocuments/show?month=' . $month . '&year=' . $year);

        if (($othersdocuments->total()) == 0) {
            return redirect()
                ->route('othersdocuments.showForm')
                ->with('presentYear', $presentYear)
                ->withErrors(__('No hay documentos en ' . $month . $year));
        } else {
            return view('othersdocuments.showOtherDocuments', compact('othersdocuments', 'month', 'year'));
        }
    }

    public function deleteOthersDocuments($id, $month, $year)
    {
        $otherDocuments = DB::Table('others_documents')
            ->where('id', '=', $id)
            ->get();

        if (count($otherDocuments) > 0) {
            $delete = Storage::delete($otherDocuments->filename);
            if ($delete) {
                $delete = DB::Table('others_documents')
                    ->where('id', '=', $id)
                    ->delete();
                if ($delete) {
                    return redirect()
                        ->route('othersdocuments.showOthersDocuments', compact('month', 'year'))
                        ->withSuccess(__('Se han eliminado correctamente todos los documentos.'));
                }
            }
        }
        return redirect()
            ->route('othersdocuments.showOthersDocuments', compact('month', 'year'))
            ->withErrors(__('Ha habido un error al intentar eliminar todos los documentos.'));
    }

    public function deleteAllOtherDocuments()
    {
        $path = 'storage/media/othersDocuments';

        if (Storage::exists($path)) {
            $delete = Storage::deleteDirectory($path);
            if ($delete) {
                Storage::makeDirectory($path, 0775, true);
                $delete = DB::table('others_documents')
                    ->delete();
                if ($delete) {
                    return redirect()
                        ->route('othersdocuments.showForm')
                        ->withSuccess(__('Se han eliminado correctamente todos los documentos.'));
                }
            }
        }
        return redirect()
            ->route('othersdocuments.showForm')
            ->withErrors(__('Ha habido un error al intentar eliminar todos los documentos.'));
    }
}
