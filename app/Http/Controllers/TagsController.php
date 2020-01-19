<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Http\Controllers\PostsController;

class TagsController extends Controller
{   
    public function index(Tag $tag)
    {
        $items['informations'] = rememberChacheWithTags(['tags'], 'informations|' . $tag->name, function() use ($tag){
            return $tag->informations()->with('tags')->latest()->simplePaginate(config('database.amountLimit'));
        });
        
        $items['posts'] = rememberChacheWithTags(['tags'], 'posts|' . $tag->name, function() use ($tag){
            return $tag->posts()->published()->with('tags')->latest()->simplePaginate(config('database.amountLimit'));
        });
        
        return view('news_posts', compact('items'));
    }
}
