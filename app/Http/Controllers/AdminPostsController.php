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
        $posts = Post::with('tags')->latest()->simplePaginate(self::AMOUNT_LIMIT);
        return view('posts', compact('posts'));
    }
}
