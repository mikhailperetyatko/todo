<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostsController extends Controller
{
    const AMOUNT_LIMIT = 3;
    const PREFIX = 'posts';
    
    public function index()
    {
        $data = Post::latest()->simplePaginate(self::AMOUNT_LIMIT);
        $prefix = self::PREFIX;
        return view('posts', compact('data', 'prefix'));
    }
    
    public function show(Post $data)
    {
        return view('posts.show', compact('data'));
    }
    
    public function create()
    {
        return view('posts.create');
    }
    
    public function store()
    {
        $attr = request()->validate([
             'title' => 'required|min:5|max:100',
            'description' => 'required|max:255',
            'body' => 'required',
            'published' => 'regex:/on/',
            'slug' => 'required|regex:/^[0-9A-z_-]+$/|unique:posts'
        ]);
        
        Post::create($attr);
        return redirect('/');
    }
    
    public function edit(Post $data)
    {
        return view('posts.edit', compact('data'));
    }
    
    public function update(Post $data)
    {
        $data->published = request()->has('published');
        $attr = request()->validate([
            'title' => 'required|min:5|max:100',
            'description' => 'required|max:255',
            'body' => 'required',
            'published' => 'regex:/on/',
            'slug' => 'required|regex:/^[0-9A-z_-]+$/|unique:posts,slug,' . $data->id 
        ]);
        
        $data->update($attr);
        return redirect('/posts/' . $data->slug);
    }
    
    public function destroy(Post $data)
    {
        $data->delete();
        return redirect('/');
    }
}
