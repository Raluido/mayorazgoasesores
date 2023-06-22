<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use ZipArchive;
use Illuminate\Support\Facades\Auth;
use App\Jobs\UploadPayrolls;
use App\Jobs\AddUsersPayrolls;
use setasign\Fpdi\Fpdi;
use Smalot\PdfParser\Parser;

class PayrollsController extends Controller
{

    public function uploadForm()
    {
        $presentYear = date("Y");

        return view('payrolls.uploadForm')
            ->with('presentYear', $presentYear);
    }

    public function uploadPayrolls(Request $request)
    {
        $file = $request->file('payrolls');

        if ($request->hasFile('payrolls')) {

            $allowedfileExtension = ['pdf'];
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);

            if ($check) {

                $filename = date('d-m-Y h.i.s a', time()) . ".pdf";
                $file->storeAs('storage/media/',  $filename);
                $filenameNoExt = pathinfo($filename, PATHINFO_FILENAME);

                $newPdf = new Fpdi();
                $newPdf->addPage();
                $newPdf->setSourceFile(public_path('storage/media/' . $filename));
                $newPdf->useTemplate($newPdf->importPage(1));
                $newFilename = sprintf('%s/%s_p%s.pdf', 'storage/media', $filenameNoExt, 1);
                $newPdf->output($newFilename, 'F');
                $pdfParser = new Parser();
                $pdf = $pdfParser->parseFile($newFilename);
                $content = $pdf->getText();
                preg_match_all('/MENS\s+[0-9]{2}\s+[A-Z]{3}\s+[0-9]{2}/', $content, $period, PREG_OFFSET_CAPTURE);


                if (!empty($period[0])) {
                    if (Storage::exists($newFilename)) {
                        Storage::delete($newFilename);
                    }
                    $month = $request->input('month');
                    $year = $request->input('year');
                    AddUsersPayrolls::dispatch($filename, $month, $year);
                    UploadPayrolls::dispatch($filename, $month, $year);
                } else {
                    if (Storage::exists($filename)) {
                        Storage::delete($filename);
                    }
                    if (Storage::exists($newFilename)) {
                        Storage::delete($newFilename);
                    }

                    return redirect()->route('payrolls.uploadForm')
                        ->withErrors(__('El documento adjuntado no tiene el formato de nómina.'));
                }
            } else {
                return redirect()->route('payrolls.uploadForm')
                    ->withErrors(__('Sólo se admiten archivos con extensión .pdf'));
            }
        } else {
            return redirect()->route('payrolls.uploadForm')
                ->withErrors(__('No has añadido ningun archivo aún.'));
        }
        return redirect()->route('payrolls.uploadForm')
            ->withSuccess(__('Las nóminas han comenzado a subirse, tardaremos unos minutos, gracias ;)'));
    }

    public function downloadForm()
    {
        $presentYear = date("Y");
        $month = null;
        $year = null;

        return view('payrolls.downloadForm', compact('month', 'year', 'presentYear'));
    }

    public function getData(Request $request)
    {
        $presentYear = date("Y");
        $month = $request->input('month');
        $year = $request->input('year');

        return view('payrolls.downloadForm', compact('month', 'year', 'presentYear'));
    }


    public function downloadPayrolls($month, $year)
    {
        $presentYear = date("Y");

        $payrolls = DB::Table('users')
            ->select('payrolls.filename')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->join('payrolls', 'payrolls.employee_id', '=', 'employees.id')
            ->where('users.nif', '=', Auth::user()->nif)
            ->where('payrolls.year', '=', $year)
            ->where('payrolls.month', '=', $month)
            ->get();

        if (count($payrolls) > 0) {

            $zipFilename = Auth::user()->nif . '_' . $month . $year . '.zip';
            $zip = new ZipArchive;

            $publicDir = public_path('storage/media/payrolls/' . $year . '/' . $month);
            $tempFolder = public_path('storage/media');

            if ($zip->open($tempFolder . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                foreach ($payrolls as $payroll) {
                    $filename = basename($payroll->filename);
                    $zip->addFile($publicDir . '/' . $filename, $filename);
                }
                $zip->close();
            }

            if (file_exists($tempFolder . '/' . $zipFilename)) {
                return response()
                    ->download($tempFolder . '/' . $zipFilename)
                    ->deleteFileAfterSend(true);
            }
        } else {
            return redirect()
                ->route('payrolls.downloadForm')
                ->with($month, $year, $presentYear)
                ->withErrors(__('Las nóminas de ' . $month . $year . ' no están disponibles.'));
        }
    }

    public function showForm()
    {
        $presentYear = date("Y");

        return view('payrolls.showForm')
            ->with('presentYear', $presentYear);
    }

    public function showPayrolls(Request $request, $month = null, $year = null)
    {
        $presentYear = date("Y");

        if ($month == null && $year == null) {
            $month = $request->input('month');
            $year = $request->input('year');
        }

        $payrolls = DB::Table('users')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->join('payrolls', 'payrolls.employee_id', '=', 'employees.id')
            ->where('payrolls.year', '=', $year)
            ->where('payrolls.month', '=', $month)
            ->select('users.nif', 'employees.dni', 'payrolls.id', 'payrolls.month', 'payrolls.year')
            ->paginate(10);

        $payrolls->setPath('/payrolls/show?month=' . $month . '&year=' . $year);

        if (($payrolls->total()) == 0) {
            return redirect()
                ->route('payrolls.showForm')
                ->with($presentYear)
                ->withErrors(__('Aún no están disponibles las nóminas de ' . $month . $year . '.'));
        } else {
            return view('payrolls.showPayrolls')
                ->with('payrolls', $payrolls);
        }
    }

    public function deletePayrolls($id, $month, $year)
    {
        $payroll = DB::Table('payrolls')
            ->where('id', '=', $id)
            ->value('filename');

        if ($payroll && Storage::exists($payroll)) {
            $delete = Storage::delete($payroll);
            if ($delete) {
                $delete = DB::Table('payrolls')
                    ->where('id', '=', $id)
                    ->delete();
                if ($delete) {
                    return redirect()
                        ->route('payrolls.showPayrolls', compact('year', 'month'))
                        ->withSuccess(__('Se ha eliminado correctamente la nómina'));
                }
            }
        }
        return redirect()
            ->route('payrolls.showPayrolls', compact('year', 'month'))
            ->withErrors(__('Ha habido un error al intentar eliminar la nómina.'));
    }

    public function deleteAllPayrolls()
    {
        $path = 'storage/media/payrolls';

        if (Storage::exists($path)) {
            $delete = Storage::deleteDirectory($path);
            if ($delete) {
                Storage::makeDirectory($path, 0775, true);
                $delete = DB::table('payrolls')
                    ->delete();
                if ($delete) {
                    return redirect()
                        ->route('payrolls.showForm')
                        ->withSuccess(__('Se han eliminado correctamente todas las nóminas'));
                }
            }
        }
        return redirect()
            ->route('payrolls.showForm')
            ->withErrors(__('Ha habido un error al intentar eliminar todas nóminas.'));
    }
}
