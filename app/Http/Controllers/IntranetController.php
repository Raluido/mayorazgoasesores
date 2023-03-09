<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IntranetController extends Controller
{
    public function index()
    {
        return view('intranet.index');
    }
}
