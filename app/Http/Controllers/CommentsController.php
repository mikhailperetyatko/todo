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
    
    public function store()
    {
        $attr = request()->validate([
            'body' => 'required'
        ]);
        $attr['owner_id'] = auth()->id();
        $comment = Comment::create($attr);
        
        return $comment; 
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
