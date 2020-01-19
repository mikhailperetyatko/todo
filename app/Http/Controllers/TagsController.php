<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Http\Controllers\PostsController;

class TagsController extends Controller
{   
    public function index(Tag $tag)
    {
        $items['informations'] = $tag->informations()->with('tags')->latest()->simplePaginate(config('database.amountLimit'));
        $items['posts'] = $tag->posts()->published()->with('tags')->latest()->simplePaginate(config('database.amountLimit'));
        return view('news_posts', compact('items'));
    }
}
