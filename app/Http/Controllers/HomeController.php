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


        libxml_use_internal_errors(true);
        $doc = new \DomDocument();
        $doc->loadHTML(file_get_contents('https://elpais.com/'));
        $xpath = new \DOMXPath($doc);
        $query = '//*/meta[starts-with(@property, \'og:\')]';
        $metas = $xpath->query($query);
        foreach ($metas as $meta) {
            // $property = $meta->getAttribute('property');
            $content = $meta->getAttribute('content');
            $result = mb_detect_encoding($content);
            $result1 = mb_convert_encoding($content, "UTF-8", "ISO-8859-1");
            // log::info($property);
            log::info($result);
            log::info($result1);
        }



        return view('home.index', compact('posts'));
    }

    public function mtn()
    {
        return view('maintenance.working');
    }

    public function legal()
    {
        return view('legal.legalNotice');
    }

    public function privacy()
    {
        return view('legal.privacyPolicy');
    }

    public function cookies()
    {
        return view('legal.cookiesPolicy');
    }
}
