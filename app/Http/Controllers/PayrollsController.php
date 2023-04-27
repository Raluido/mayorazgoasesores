<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payroll;
use DB;
use ZipArchive;
use Illuminate\Support\Facades\Auth;
use App\Jobs\UploadPayrolls;
use App\Jobs\AddUsersAuto;
use App\Jobs\Test;

class PayrollsController extends Controller
{

    public function uploadForm()
    {
        $presentYear = date("Y");

        return view('payrolls.uploadForm', compact('presentYear'));
    }

    public function uploadPayrolls(Request $request)
    {
        $presentYear = date("Y");

        $file = $request->file('payrolls');

        if ($request->hasFile('payrolls')) {
            $allowedfileExtension = ['pdf'];
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);
            if ($check) {
                $filenamewithextension = date('d-m-Y h.i.s a', time()) . ".pdf";
                $file->storeAs('storage/media/',  $filenamewithextension);
                $month = $request->input('month');
                $year = $request->input('year');
                AddUsersAuto::dispatch($filenamewithextension);
                UploadPayrolls::dispatch($filenamewithextension, $month, $year);
            } else {
                echo '<div class=""><strong>Warning!</strong> Sólo se admiten archivos con extensión .pdf</div>';

                return view('payrolls.uploadForm')->with('presentYear', $presentYear);
            }
        } else {
            echo '<div class=""><strong>Warning!</strong> No has añadido ningun archivo aún.</div>';

            return view('payrolls.uploadForm')->with('presentYear', $presentYear);
        }

        return view('payrolls.uploadForm')->with('presentYear', $presentYear)->with('successMsg', "Las nóminas han comenzado a subirse, tardaremos unos minutos, gracias ;)");
    }

    public function downloadForm()
    {
        $presentYear = date("Y");
        $month = null;
        $year = null;

        return view('payrolls.downloadForm')
            ->with('month', $month)
            ->with('year', $year)
            ->with('presentYear', $presentYear);
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

        $files = DB::Table('users')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->join('payrolls', 'payrolls.employee_id', '=', 'employees.id')
            ->where('users.nif', '=', Auth::user()->nif)
            ->where('payrolls.year', '=', $year)
            ->where('payrolls.month', '=', $month)
            ->select('payrolls.filename')
            ->get()
            ->toArray();

        if ($files != null) {

            $zipFilename = Auth::user()->nif . '_' . $month . $year . '.zip';
            $zip = new ZipArchive;

            $publicDir = public_path('storage/media/payrolls/' . $year . '/' . $month);
            $tempFolder = public_path('storage/media/payrolls');

            if ($zip->open($tempFolder . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                foreach ($files as $file) {
                    $filename = basename((array_values((array)$file))[0]);
                    $temp = (array_values((array)$filename))[0];
                    $zip->addFile($publicDir . '/' . $temp, $temp);
                }
                $zip->close();
            }

            if (file_exists($tempFolder . '/' . $zipFilename)) {
                return response()->download($tempFolder . '/' . $zipFilename)->deleteFileAfterSend(true);
            }
        } else {
            echo '<div class=""><strong>Warning!</strong> Las nóminas de ' . $month . $year . ' no están disponibles.<div>';
        }

        return view('payrolls.downloadForm', compact('month', 'year', 'presentYear'));
    }

    public function showForm()
    {
        $presentYear = date("Y");

        return view('payrolls.showForm')->with('presentYear', $presentYear);
    }

    public function showPayrolls(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $payrolls = DB::Table('users')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->join('payrolls', 'payrolls.employee_id', '=', 'employees.id')
            ->where('payrolls.year', '=', $year)
            ->where('payrolls.month', '=', $month)
            ->select('users.nif', 'employees.dni', 'payrolls.id', 'payrolls.month', 'payrolls.year')
            ->paginate(10);

        if ($payrolls[0] == null) {
            echo '<div class=""> Aún no están disponibles las nóminas de ' . $month . $year . '<div>';
        }
        return view('payrolls.showPayrolls', compact('payrolls', 'month', 'year'));
    }

    public function deletePayrolls($id)
    {
        $payrollId = DB::Table('payrolls')->where('id', '=', $id)->value('filename');
        unlink($payrollId);

        DB::Table('payrolls')->where('id', '=', $id)->delete();

        return redirect()->route('payrolls.showForm')->with('payrolls');
    }

    public function deleteAllPayrolls()
    {
        File::deleteDirectory(public_path('/storage/media/payrolls'));
        $path = public_path('/storage/media/payrolls');
        File::makeDirectory($path, 0777, true);
        DB::table('payrolls')->delete();

        return redirect()->route('payrolls.showForm')->with('payrolls');
    }
}




// public function downloadPayrolls($month, $year)
//     {
//         $presentYear = date("Y");

//         $files = DB::Table('users')
//             ->join('employees', 'employees.user_id', '=', 'users.id')
//             ->join('payrolls', 'payrolls.employee_id', '=', 'employees.id')
//             ->where('users.nif', '=', Auth::user()->nif)
//             ->where('payrolls.year', '=', $year)
//             ->where('payrolls.month', '=', $month)
//             ->select('payrolls.filename')
//             ->get()
//             ->toArray();

//         if ($files != null) {

//             $zipFilename = Auth::user()->nif . '_' . $month . $year . '.zip';
//             $zip = new ZipArchive;

//             $public_dir = public_path('storage/media/payrolls/' . $year . '/' . $month);

//             if ($zip->open($public_dir . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
//                 foreach ($files as $file) {
//                     $filename = basename((array_values((array)$file))[0]);
//                     $temp = (array_values((array)$filename))[0];
//                     $zip->addFile($public_dir . '/' . $temp, $temp);
//                 }
//                 $zip->close();
//             }

//             if (file_exists($public_dir . '/' . $zipFilename)) {
//                 return response()->download(public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $zipFilename))->deleteFileAfterSend(true);
//             }
//         } else {
//             echo '<div class=""><strong>Warning!</strong> Las nóminas de ' . $month . $year . ' no están disponibles.<div>';
//         }

//         return view('payrolls.downloadForm', compact('month', 'year', 'presentYear'));
//     }