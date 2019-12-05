<?php

namespace App\Http\Controllers;

use App\Information;
use Illuminate\Http\Request;
use App\Tag;

class AdminInformationsController extends Controller
{
    const AMOUNT_LIMIT = 3;
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:administrate');
    }
    
    protected function getValidateRulesForCreate() : array
    {
        return [
            'title' => 'required|min:5|max:100',
            'body' => 'required',
            'slug' => 'required|regex:/^[0-9A-z_-]+$/|unique:informations'
        ];
    }
    
    protected function getValidateRulesForUpdate(Information $information) : array
    {
        $rules = $this->getValidateRulesForCreate();
        $rules['slug'] .= ',slug,' . $information->id;
        return $rules;
    }
    
    public function getSyncTags(Information $information)
    {   
        $informationTags = $information->tags->keyBy('name');
        $tags = collect(explode(',', request('tags')))->keyBy(function ($item) {
            return $item;
        });
        
        $syncIds = $informationTags->intersectByKeys($tags)->pluck('id')->toArray();
        $tagsToAttach = $tags->diffKeys($informationTags);
        foreach ($tagsToAttach as $tag) {
            if (!empty($tag)) {
                $tag = Tag::firstOrCreate(['name' => $tag]);
                $syncIds[] = $tag->id;
            }
        }
        
        $information->tags()->sync($syncIds);
    }
    
    public function index()
    {
        $informations = Information::with('tags')->latest()->simplePaginate(self::AMOUNT_LIMIT);
        return view('informations', compact('informations'));
    }

    public function create()
    {
        return view('informations.create');
    }

    public function store(Request $request)
    {
        $attr = request()->validate($this->getValidateRulesForCreate());
        $attr['owner_id'] = auth()->id();
        $this->getSyncTags($information = Information::create($attr));
        
        flash('success');
        return redirect(getUrlForRedirect($this, 'show', $information));
    }

    public function show(Information $information)
    {
        return view('informations.show', compact('information'));
    }

    public function edit(Information $information)
    {
        return view('informations.edit', compact('information'));
    }

    public function update(Request $request, Information $information)
    {
        $attr = request()->validate($this->getValidateRulesForUpdate($information));
        $information->update($attr);
        $this->getSyncTags($information);
        
        flash('success');
        return redirect(getUrlForRedirect($this, 'show', $information));
    }

    public function destroy(Information $information)
    {
        $information->delete();
        
        flash('warning', 'Новость удалена');
        return redirect(getUrlForRedirect($this, 'index'));
    }
    
}
