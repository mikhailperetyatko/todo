<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected function getRules()
    {
        return [
            'name' => 'required|min:3|max:255',
        ];
    }
    
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->authorize(Tag::class);

        $tag = (new Tag)->fill($request->validate($this->getRules()));
        $tag->owner()->associate(auth()->user());
        $tag->save();
        
        return $tag;
    }

    public function show(Tag $tag)
    {
        //
    }

    public function edit(Tag $tag)
    {
        //
    }
    
    public function update(Request $request, Tag $tag)
    {
        $this->authorize($tag);
        
        $tag->fill($request->validate($this->getRules()))->save();
        
        return $tag;
    }

    public function destroy(Tag $tag)
    {
        $this->authorize($tag);
        
        $tag->delete();
        
        return ['result' => true];
    }
}
