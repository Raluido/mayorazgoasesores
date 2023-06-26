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
use App\Models\Payroll;

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

                $filename = date('d-m-Y his a', time()) . '.' . $extension;
                $file->storeAs('storage/media/',  $filename);
                $fileNameNoExt = pathinfo('storage/media/' . $filename, PATHINFO_FILENAME);

                $newPdf = new Fpdi();
                $newPdf->addPage();
                $newPdf->setSourceFile(public_path('storage/media/' . $filename));
                $newPdf->useTemplate($newPdf->importPage(1));
                $newFilename = $fileNameNoExt . '_chk.' . $extension;
                $newPdf->output(public_path('storage/media/' . $newFilename), 'F');
                $pdfParser = new Parser();
                $pdf = $pdfParser->parseFile(public_path('storage/media/' . $newFilename));
                $content = $pdf->getText();
                preg_match_all('/MENS\s+[0-9]{2}\s+[A-Z]{3}\s+[0-9]{2}/', $content, $period, PREG_OFFSET_CAPTURE);


                if (!empty($period[0])) {
                    if (Storage::exists('/storage/media/' . $newFilename)) {
                        Storage::delete('/storage/media/' . $newFilename);
                    }
                    $month = $request->input('month');
                    $year = $request->input('year');
                    AddUsersPayrolls::dispatch($filename, $month, $year);
                    UploadPayrolls::dispatch($filename, $month, $year);
                } else {
                    if (Storage::exists('storage/media/' . $filename)) {
                        Storage::delete('storage/media/' . $filename);
                    }
                    if (Storage::exists('/storage/media/' . $newFilename)) {
                        Storage::delete('/storage/media/' . $newFilename);
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

        $payrolls = Db::Table('payrolls')
            ->join('employee_user', 'payrolls.employee_user_id', '=', 'employee_user.id')
            ->join('employees', 'employee_user.employee_id', '=', 'employees.id')
            ->join('users', 'employee_user.user_id', '=', 'users.id')
            ->where('users.id', Auth::user()->id)
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        if (count($payrolls) > 0) {

            $zipFilename = Auth::user()->nif . '_' . $month . $year . '.zip';
            $zip = new ZipArchive;

            $path = public_path('storage/media');

            if ($zip->open($path . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                foreach ($payrolls as $payroll) {
                    $zip->addFile($payroll->filename);
                }
                $zip->close();
            }

            if (Storage::exists('storage/media/' . $zipFilename)) {
                return response()
                    ->download($path . '/' . $zipFilename)
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

        $payrolls = Db::Table('payrolls')
            ->join('employee_user', 'payrolls.employee_user_id', '=', 'employee_user.id')
            ->join('employees', 'employee_user.employee_id', '=', 'employees.id')
            ->join('users', 'employee_user.user_id', '=', 'users.id')
            ->where('month', $month)
            ->where('year', $year)
            ->paginate(10);

        $payrolls->setPath('/payrolls/show?month=' . $month . '&year=' . $year);

        if ($payrolls->total() == 0) {
            return redirect()
                ->route('payrolls.showForm')
                ->with($presentYear)
                ->withErrors(__('Aún no están disponibles las nóminas de ' . $month . $year . '.'));
        } else {
            return view('payrolls.showPayrolls')
                ->with('payrolls', $payrolls);
        }
    }

    public function deletePayrolls(Payroll $payroll, $month, $year)
    {
        $payroll = DB::Table('payrolls')
            ->where('id', '=', $payroll->id)
            ->value('filename');

        if ($payroll && Storage::exists($payroll)) {
            $delete = Storage::delete($payroll);
            if ($delete) {
                $delete = DB::Table('payrolls')
                    ->where('id', '=', $payroll->id)
                    ->delete();
                if ($delete) {
                    return redirect()
                        ->route('payrolls.showPayrolls', compact('year', 'month'))
                        ->withSuccess(__('Se ha eliminado correctamente la nómina'));
                } else {
                    return redirect()
                        ->route('payrolls.showPayrolls', compact('year', 'month'))
                        ->withErrors(__('Ha habido un error al intentar eliminar la nómina.'));
                }
            } else {
                return redirect()
                    ->route('payrolls.showPayrolls', compact('year', 'month'))
                    ->withErrors(__('Ha habido un error al intentar eliminar la nómina.'));
            }
        } else {
            return redirect()
                ->route('payrolls.showPayrolls', compact('year', 'month'))
                ->withErrors(__('Ha habido un error al intentar eliminar la nómina.'));
        }
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
                } else {
                    return redirect()
                        ->route('payrolls.showForm')
                        ->withErrors(__('Ha habido un error al intentar eliminar todas nóminas.'));
                }
            } else {
                return redirect()
                    ->route('payrolls.showForm')
                    ->withErrors(__('Ha habido un error al intentar eliminar todas nóminas.'));
            }
        } else {
            return redirect()
                ->route('payrolls.showForm')
                ->withErrors(__('Ha habido un error al intentar eliminar todas nóminas.'));
        }
    }
}
