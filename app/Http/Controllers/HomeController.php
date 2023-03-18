<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $posts = DB::table('posts')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('home.index', compact('posts'));
    }
}
