<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostsController extends Controller
{
    const AMOUNT_LIMIT = 3;
    const PREFIX = 'posts';
    
    public function list()
    {
        $data = Post::latest()->simplePaginate(self::AMOUNT_LIMIT);
        $prefix = self::PREFIX;
        return view('posts', compact('data', 'prefix'));
    }
    
    public function show(Post $slug)
    {
        return view('posts.view', compact('slug'));
    }
    
    public function create()
    {
        return view('posts.create');
    }
    
    public function store()
    {
        $this->validate(request(), [
            'title' => 'required|min:5|max:100',
            'description' => 'required|max:255',
            'body' => 'required',
            'published' => 'regex:/on/',
            'slug' => 'required|regex:/^[0-9A-z_-]+$/|unique:posts'
        ]);
        Post::create(request()->all());
        return redirect('/');
    }
}
