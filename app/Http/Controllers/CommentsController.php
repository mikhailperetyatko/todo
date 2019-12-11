<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\Information;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    private function store()
    {
        $attr = request()->validate([
            'body' => 'required'
        ]);
        $attr['owner_id'] = auth()->id();
        
        return Comment::create($attr);
    }    
    
    public function toPost(Request $request, Post $post)
    {
        $post->comments()->save($this->store());
        return back();
    }
    
    public function toInformation(Request $request, Information $information)
    {
        $information->comments()->save($this->store());
        return back();
    }
}
