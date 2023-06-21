<?php

namespace App\Http\Controllers;

use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Fpdi;
use DB;
use ZipArchive;
use Illuminate\Support\Facades\Auth;
use App\Jobs\UploadCostsImputs;
use App\Jobs\AddUsersCostsImputs;

class CostsImputsController extends Controller
{

    public function uploadForm()
    {
        $presentYear = date("Y");

        return view('costsimputs.uploadForm')
            ->with('presentYear', $presentYear);
    }

    public function uploadCostsImputs(Request $request)
    {
        $file = $request->file('costsimputs');

        if ($request->hasFile('costsimputs')) {

            $allowedfileExtension = ['pdf'];
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);

            if ($check) {

                // checking if its a costsimputs format

                $filename = date('d-m-Y h.i.s a', time()) . ".pdf";
                $file->storeAs('storage/media/',  $filename);
                $filenameNoExt = pathinfo($filename, PATHINFO_FILENAME);

                $newPdf = new Fpdi();
                $newPdf->addPage();
                $newPdf->setSourceFile(public_path('storage/media/' . $filename));
                $newPdf->useTemplate($newPdf->importPage(1));
                $newFilename = sprintf('%s/%s_p%s.pdf', public_path('storage/media'), $filenameNoExt, 1);
                $newPdf->output($newFilename, 'F');
                $pdfParser = new Parser();
                $pdf = $pdfParser->parseFile($newFilename);
                $content = $pdf->getText();
                preg_match_all('/PERIODO\s+DEL\s+[0-9]{2}\/[0-9]{2}\/[0-9]{2}/', $content, $period, PREG_OFFSET_CAPTURE);

                // end

                if (!empty($period[0])) {
                    if (file_exists(public_path('storage/media/' . $newFilename))) {
                        unlink(public_path('storage/media/' . $newFilename));
                    }
                    $month = $request->input('month');
                    $year = $request->input('year');
                    AddUsersCostsImputs::dispatch($filename, $month, $year);
                    UploadCostsImputs::dispatch($filename, $month, $year);
                } else {
                    if (file_exists(public_path('storage/media/' . $filename))) {
                        unlink(public_path('storage/media/' . $filename));
                    }
                    if (file_exists(public_path('storage/media/' . $newFilename))) {
                        unlink(public_path('storage/media/' . $newFilename));
                    }

                    return redirect()
                        ->route('costsimputs.uploadForm')
                        ->withErrors(__('El documento adjuntado no tiene el formato de imputación de costes.'));
                }
            } else {
                return redirect()
                    ->route('costsimputs.uploadForm')
                    ->withErrors(__('Sólo se admiten archivos con extensión .pdf'));
            }
        } else {
            return redirect()
                ->route('costsimputs.uploadForm')
                ->withErrors(__('No has añadido ningun archivo aún.'));
        }
        return redirect()
            ->route('costsimputs.uploadForm')
            ->withSuccess(__('Los documentos de imputación de costes han comenzado a subirse, tardaremos unos minutos, gracias ;)'));
    }

    public function downloadForm()
    {
        $month = null;
        $year = null;
        $presentYear = date("Y");

        return view('costsimputs.downloadForm', compact('month', 'year', 'presentYear'));
    }

    public function getData(Request $request)
    {
        $presentYear = date("Y");
        $month = $request->input('month');
        $year = $request->input('year');

        return view('costsimputs.downloadForm', compact('month', 'year', 'presentYear'));
    }


    public function downloadCostsImputs($month, $year)
    {
        $presentYear = date("Y");

        $files = DB::Table('users')
            ->select('costs_imputs.filename')
            ->join('costs_imputs', 'costs_imputs.user_id', '=', 'users.id')
            ->where('users.nif', '=', Auth::user()->nif)
            ->where('costs_imputs.year', '=', $year)
            ->where('costs_imputs.month', '=', $month)
            ->get();

        if (count($files) > 0) {

            $zipFilename = Auth::user()->nif . '_' . $month . $year . '.zip';
            $zip = new ZipArchive;

            $publicDir = public_path('storage/media/costsImputs/' . $year . '/' . $month);
            $tempFolder = public_path('storage/media');

            if ($zip->open($tempFolder . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                foreach ($files as $file) {
                    $filename = basename($file->filename);
                    $zip->addFile($publicDir . '/' . $filename, $filename);
                }
                $zip->close();
            }

            if (file_exists($tempFolder . '/' . $zipFilename)) {
                return response()->download($tempFolder . '/' . $zipFilename)->deleteFileAfterSend(true);
            }
        } else {
            return redirect()
                ->route('costsimputs.downloadForm')
                ->with($month, $year, $presentYear)
                ->withErrors(__('Los modelos de imputación de costes de ' . $month . $year . ' no están disponibles.'));
        }
    }

    public function showForm()
    {
        $presentYear = date("Y");

        return view('costsimputs.showForm')
            ->with('presentYear', $presentYear);
    }

    public function showCostsImputs(Request $request, $year = null, $month = null)
    {
        $presentYear = date("Y");

        if ($month == null && $year == null) {
            $month = $request->input('month');
            $year = $request->input('year');
        }

        $costsimputs = DB::Table('users')
            ->select('users.nif', 'costs_imputs.month', 'costs_imputs.year', 'costs_imputs.user_id')
            ->join('costs_imputs', 'costs_imputs.user_id', '=', 'users.id')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->groupBy('users.nif')
            ->paginate(10);

        $costsimputs->setPath('/costsimputs/show?month=' . $month . '&year=' . $year);

        if (count($costsimputs) > 0) {
            return view('costsimputs.showCostsImputs')
                ->with('costsimputs', $costsimputs);
        } else {
            return redirect()
                ->route('costsimputs.showForm')
                ->with('presentYear', $presentYear)
                ->withErrors(__('Aún no están disponibles los modelos de imputación de costes de ' . $month . $year . '.'));
        }
    }


    public function deleteCostsImputs($id, $year, $month)
    {
        $presentYear = date("Y");

        $costsimputId = DB::Table('costs_imputs')
            ->select('filename', 'id')
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->where('user_id', '=', $id)
            ->get();

        if (count($costsimputId) > 0) {
            foreach ($costsimputId as $index) {
                if (Storage::exists($index->filename)) {
                    $delete = Storage::delete($index->filename);
                    if ($delete) {
                        $delete = DB::Table('costs_imputs')
                            ->where('id', '=', $index->id)
                            ->delete();
                        if (!$delete) {
                            return redirect()
                                ->route('costsimputs.showCostsImputs', compact('month', 'year'))
                                ->withErrors(__('Ha habido un error al intentar eliminar el modelo de imputación de costes.'));
                        }
                    } else {
                        return redirect()
                            ->route('costsimputs.showCostsImputs', compact('month', 'year'))
                            ->withErrors(__('Ha habido un error al intentar eliminar el modelo de imputación de costes.'));
                    }
                } else {
                    return redirect()
                        ->route('costsimputs.showCostsImputs', compact('month', 'year'))
                        ->withErrors(__('Ha habido un error al intentar eliminar el modelo de imputación de costes.'));
                }
            }
            return redirect()
                ->route('costsimputs.showCostsImputs', compact('month', 'year'))
                ->withSuccess(__('Hemos eliminado correctamente el modelos de imputación de costes seleccionado.'));
        }
    }

    public function deleteAllCostsImputs()
    {
        $path = 'storage/media/costsImputs';

        if (Storage::exists($path)) {
            $delete = Storage::deleteDirectory($path);
            if ($delete) {
                Storage::makeDirectory($path, 0775, true);
                $delete = DB::table('costs_imputs')->delete();
                if ($delete) {
                    return redirect()
                        ->route('costsimputs.showForm')
                        ->withSuccess(__('Se han eliminado correctamente todos los modelos de imputación de costes.'));
                } else {
                    return redirect()
                        ->route('costsimputs.showForm')
                        ->withErrors(__('Ha habido un error al intentar eliminar todos los modelos de imputación de costes.'));
                }
            } else {
                return redirect()
                    ->route('costsimputs.showForm')
                    ->withErrors(__('Ha habido un error al intentar eliminar la carpeta con los modelos de imputación de costes.'));
            }
        }
    }
}
