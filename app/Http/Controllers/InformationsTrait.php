<?php

namespace App\Http\Controllers;

use App\Information;
use Illuminate\Http\Request;
use App\Tag;

trait InformationsTrait
{
    public function index()
    {
        $informations = Information::with('tags')->latest()->simplePaginate(config('database.amountLimit'));
        return view('informations', compact('informations'));
    }
    
    public function show(Information $information)
    {
        $comments = $information->comments()->latest()->simplePaginate(config('database.amountLimit'));
        return view('informations.show', compact('information', 'comments'));
    }
}
