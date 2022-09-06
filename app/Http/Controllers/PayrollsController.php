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
                $filenamewithextension = "addPayrolls.pdf";
                $file->storeAs('storage/media/',  $filenamewithextension);
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


    public function downloadPayrolls($month, $year)
    {
        if ($month || $year != null) {


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

                $public_dir = public_path('storage/media/payrolls/' . $year . '/' . $month);

                if ($zip->open($public_dir . '/' . $zipFilename, ZipArchive::CREATE) === TRUE) {
                    foreach ($files as $file) {
                        $temp = (array_values((array)$file))[0];
                        $zip->addFile($public_dir . '/' . $temp, $temp);
                    }
                    $zip->close();
                }

                if (file_exists($public_dir . '/' . $zipFilename)) {
                    return response()->download(public_path('storage/media/payrolls/' . $year . '/' . $month . '/' . $zipFilename))->deleteFileAfterSend(true);
                }
            } else {
                echo '<div class="alert alert-warning"><strong>Warning!</strong> Las nóminas de ' . $month . $year . ' no están disponibles.<div>';
            }
        } else {
            echo '<div class="alert alert-warning"><strong>Warning!</strong> Debes elegir un mes y un año.<div>';
        }

        return view('payrolls.downloadForm')->with('month', $month)->with('year', $year);
    }

    public function showForm()
    {
        return view('payrolls.showForm');
    }

    public function showPayrolls(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $payrolls = DB::Table('users')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->join('payrolls', 'payrolls.employee_id', '=', 'employees.id')
            ->select('users.nif', 'employees.dni', 'payrolls.id', 'payrolls.month', 'payrolls.year')
            ->paginate(10);

        if ($payrolls[0] != null) {
            return view('payrolls.showPayrolls', compact('payrolls', 'month', 'year'));
        } else {
            echo '<div class="alert alert-warning"> Aún no están disponibles las nóminas de ' . $month . $year . '<div>';
        }
    }

    public function deletePayrolls(Payroll $payroll, $month, $year)
    {
        $payroll->delete();

        $payrolls = DB::Table('payrolls')->where('year', $year)->where('month', $month)->paginate(10);
        unlink(public_path('/storage/media/payrolls/' . $year . '/' . $month . '/' . $payroll->filename));

        return redirect()->route('payrolls.showForm')->with('payrolls');
    }

    public function deleteAllPayrolls($month, $year)
    {
        DB::table('payrolls')->where('year', $year)->where('month', $month)->delete();

        File::deleteDirectory(public_path('/storage/media/payrolls/' . $year . '/' . $month));

        $payrolls = DB::Table('payrolls')->where('year', $year)->where('month', $month)->paginate(10);

        return redirect()->route('payrolls.showForm')->with('payrolls');
    }
}
