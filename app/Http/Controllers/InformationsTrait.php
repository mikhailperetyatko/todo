<?php

namespace App\Http\Controllers;

use App\Information;
use Illuminate\Http\Request;
use App\Tag;

trait InformationsTrait
{
    public function index()
    {
        $informations = rememberCacheWithTags([Information::class], 'informations|page' . (request()->input('page') ?? 1), function() {
            return Information::with('tags')->latest()->simplePaginate(config('database.amountLimit'));
        });
        return view('informations', compact('informations'));
    }
    
    public function show(string $slug)
    {
        $information = rememberCacheWithTags([Information::class], 'information|' . $slug, function() use ($slug){
            return Information::where('slug', $slug)->firstOrFail();
        });
        
        $comments = rememberCacheWithTags([\App\Comment::class, Information::class], 'information|' . $slug . '|page|' . (request()->input('page') ?? 1), function() use ($information){
            return $information->comments()->latest()->simplePaginate(config('database.amountLimit'));
        });
        
        return view('informations.show', compact('information', 'comments'));
    }
}
