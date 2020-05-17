<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Storage;
use App\User;
use App\Project;

class StoragesController extends Controller
{
    protected function getValidateRules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'description' => 'string|min:3|max:65535|nullable',
            'projectsToSync.*' => 'in:"on"',
            'type' => ['regex:/^(' . implode('|', array_column(config('app.storages'), 'value')) . ')$/', 'required'],
        ];
    }
    
    public function index()
    {
        $this->authorize(Storage::class);
        $user = auth()->user();
        
        return view('home.storages.index', [
            'user' => $user,
            'storages' => $user->storages,
        ]);
    }
    
    public function extendToken(Request $request, Storage $storage)
    {
        $this->authorize($storage);
        
        return redirect($storage->url);
    }
    
    public function create()
    {
        $this->authorize(Storage::class);
        $user = auth()->user();
        
        return view('home.storages.create', [
            'user' => $user,
        ]);
    }
    
    protected function assign(Request $request, Storage $storage)
    {
        $user = auth()->user();
        
        $attr = $request->validate($this->getValidateRules());
        $attr['token_expires_at'] = $storage->token_expires_at ?? Carbon::now();
        
        $storage->owner()->associate($user);
        
        $projectsToSync = $attr['projectsToSync'] ?? [];
        unset($attr['projectsToSync']);
        
        
        $storage->fill($attr);
        
        $storage->save();
        $this->syncProjects($storage, $projectsToSync);
        
        return $storage;
    }

    public function store(Request $request)
    {
        $this->authorize(Storage::class);
        $storage = $this->assign($request, new Storage);
        
        return redirect($storage->url);
    }
    
    protected function syncProjects(Storage $storage, array $projectsToSync = [])
    {
        $projects = [];
        
        foreach(auth()->user()->projects()->pluck('id') as $project) {
            if (array_key_exists($project, $projectsToSync)) $projects[] = $project;
        }
        
        $storage->projects()->sync($projects);
        
        return $this;
    }

    public function show(Project $project, Storage $storage)
    {
        //
    }

    public function edit(Project $project, Storage $storage)
    {
        $this->authorize($storage);
        $user = auth()->user();
        
        return view('home.storages.edit', compact('storage', 'user'));
        
    }

    public function update(Request $request, Storage $storage)
    {
        $this->authorize($storage);
        $storage = $this->assign($request, $storage);
        
        flash('success');
        return redirect('/home/storages');
    }

    public function destroy(Request $request, Storage $storage)
    {
        $this->authorize($storage);
        $storage->delete();
        
        flash('warning', 'Хранилище успешно удалено');
        return redirect('/home/storages');
    }
}