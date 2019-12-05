<?php

namespace App\Http\Controllers;

use App\Information;
use Illuminate\Http\Request;

class InformationsController extends Controller
{
    const AMOUNT_LIMIT = 3;
    
    public function index()
    {
        $informations = Information::with('tags')->latest()->simplePaginate(self::AMOUNT_LIMIT);
        return view('informations', compact('informations'));
    }

    public function show(Information $information)
    {
        return view('informations.show', compact('information'));
    }
    
}
