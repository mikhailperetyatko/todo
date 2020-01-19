<?php

namespace App\Http\Controllers;

use App\Information;
use Illuminate\Http\Request;
use App\Tag;

trait InformationsTrait
{
    public function index()
    {
        $informations = rememberChacheWithTags(['informations'], 'informations|page' . (request()->input('page') ?? 1), function() {
            return Information::with('tags')->latest()->simplePaginate(config('database.amountLimit'));
        });
        return view('informations', compact('informations'));
    }
    
    public function show(string $slug)
    {
        $information = rememberChacheWithTags(['information'], 'information|' . $slug, function() use ($slug){
            return Information::where('slug', $slug)->firstOrFail();
        });
        
        $comments = rememberChacheWithTags(['comments'], 'information|' . $slug . '|page|' . (request()->input('page') ?? 1), function() use ($information){
            return $information->comments()->latest()->simplePaginate(config('database.amountLimit'));
        });
        
        return view('informations.show', compact('information', 'comments'));
    }
}
