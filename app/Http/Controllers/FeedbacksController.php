<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Feedback;

class FeedbacksController extends Controller
{
    const AMOUNT_LIMIT = 3;
    const PREFIX = 'admin/feedbacks';
    
    public function list()
    {
        $data = Feedback::latest()->simplePaginate(self::AMOUNT_LIMIT);
        return view('admin.feedbacks', compact('data'));
    }
    
    public function store()
    {
        $attr = request()->validate([
            'email' => 'required|email|max:255',
            'body' => 'required'
        ]);
        
        Feedback::create($attr);
        return redirect('/');
    }
    
    
}
