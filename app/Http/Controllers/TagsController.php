<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Information;
use App\Post;
use App\Http\Controllers\PostsController;

class TagsController extends Controller
{   
    public function index(Tag $tag)
    {
        $items['informations'] = rememberCacheWithTags([Tag::class, Information::class], 'informations|' . $tag->name, function() use ($tag){
            return $tag->informations()->with('tags')->latest()->simplePaginate(config('database.amountLimit'));
        });
        
        $items['posts'] = rememberCacheWithTags([Tag::class, Post::class], 'posts|' . $tag->name, function() use ($tag){
            return $tag->posts()->published()->with('tags')->latest()->simplePaginate(config('database.amountLimit'));
        });
        
        return view('news_posts', compact('items'));
    }
}
