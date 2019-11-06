<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Tag;

class PostsController extends Controller
{
    const AMOUNT_LIMIT = 3;
    
    public function index()
    {
        $posts = Post::with('tags')->latest()->simplePaginate(self::AMOUNT_LIMIT);
        return view('posts', compact('posts'));
    }
    
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }
    
    public function create()
    {
        return view('posts.create');
    }
    
    public function store()
    {
        $attr = request()->validate([
            'title' => 'required|min:5|max:100',
            'description' => 'required|max:255',
            'body' => 'required',
            'slug' => 'required|regex:/^[0-9A-z_-]+$/|unique:posts'
        ]);
        $attr['published'] = request()->has('published');
        $this->getSyncTags(Post::create($attr));
        return redirect('/');
    }
    
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }
    
    public function update(Post $post)
    {
        $post->published = request()->has('published');
        $attr = request()->validate([
            'title' => 'required|min:5|max:100',
            'description' => 'required|max:255',
            'body' => 'required',
            'slug' => 'required|regex:/^[0-9A-z_-]+$/|unique:posts,slug,' . $post->id 
        ]);
        $post->update($attr);
        $this->getSyncTags($post);
        return redirect('/posts/' . $post->slug);
    }
    
    public function getSyncTags(Post $post)
    {
        $postTags = $post->tags->keyBy('name');
        $tags = collect(explode(',', request('tags')))->keyBy(function ($item) {
            return $item;
        });
        
        $syncIds = $postTags->intersectByKeys($tags)->pluck('id')->toArray();
        $tagsToAttach = $tags->diffKeys($postTags);
        foreach ($tagsToAttach as $tag) {
            if (!empty($tag)) {
                $tag = Tag::firstOrCreate(['name' => $tag]);
                $syncIds[] = $tag->id;
            }
        }
        
        $post->tags()->sync($syncIds);
    }
    
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect('/');
    }
}
