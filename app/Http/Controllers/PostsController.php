<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostsController extends Controller
{

    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function showAll()
    {
        $posts = Post::all();
        return view('posts.showAll', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required',
        //     'subtitle' => 'required',
        //     'body' => 'required',
        // ]);

        $post = new Post();
        $post->link = $request->input('link', 1);
        $post->title = $request->input('title');
        $post->subtitle = $request->input('subtitle');
        $post->body = $request->input('body');
        $post->published_at = $request->input('published_at');
        $post->save();

        $posts = DB::table('posts')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Post $post, Request $request)
    {
        // $request->validate([
        //     'title' => 'required',
        //     'subtitle' => 'required',
        //     'body' => 'required',
        // ]);

        $post->link = $request->input('link', 1);
        $post->title = $request->input('title');
        $post->subtitle = $request->input('subtitle');
        $post->body = $request->input('body');
        $post->published_at = $request->input('published_at');

        $post->save();
        return redirect('/posts')->with('success', 'Se ha actualizado correctamente!');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect('/posts')->with('success', 'Se ha eliminado correctamente!');
    }
}
