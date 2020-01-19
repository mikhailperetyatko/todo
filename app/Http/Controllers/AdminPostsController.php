<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Tag;

class AdminPostsController extends PostsController
{   
    public $prefix;
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:administrate');
    }
    
    public function index()
    {
        $posts = rememberChacheWithTags(['posts'], 'admin|posts|page' . (request()->input('page') ?? 1), function() {
            return Post::with('tags')->latest()->simplePaginate(config('database.amountLimit'));
        });
        return view('posts', compact('posts'));
    }
}
