<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Http\Controllers\PostsController;

class TagsController extends Controller
{   
    public function index(Tag $tag)
    {
        $posts = $tag->posts()->published()->with('tags')->latest()->simplePaginate(PostsController::AMOUNT_LIMIT);
        return view('posts', compact('posts'));
    }
}
