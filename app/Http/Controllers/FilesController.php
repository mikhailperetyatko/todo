<?php

namespace App\Http\Controllers;

use App\File;
use App\Subtask;
use App\Storage;
use App\Jobs\DownloadFile;
use App\Jobs\DeleteFile;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Subtask $subtask)
    {
        if (! $subtask->task->project->team->users()->where('user_id', auth()->user()->id)->exists()) abort(403);
        
        return json_encode([
            'files' => $subtask->files,
            'storages' => auth()->user()->storages->merge($subtask->task->project->storages)->unique(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Subtask $subtask)
    {
        $this->authorize(File::class);
        return view('home.files.create', [
            'subtask' => $subtask,
            'storages' => auth()->user()->storages->merge($subtask->task->project->storages)->unique(),
        ]);
    }
    
    protected function assign(Request $request, Subtask $subtask, File $file)
    {
        $user = auth()->user();
        $storages = $user->storages->merge($subtask->task->project->storages)->unique();
        
        $attr = $request->validate([
            'name' => 'min:3|max:255|nullable',
            'description' => 'string|min:3|max:65535|nullable',
            'file' => ($file->id ? 'nullable' : 'required|mimes:jpg,jpeg,gif,pdf,docx,doc,xlsx,xls,rtf,zip,rar,7z,xml') . '|max:' . config('app.maxFileSize'),
            'id' => 'integer|nullable',
            'storage' => 'required|integer',
        ]);
        
        if ($storages->isEmpty() || ! $storages->contains('id', $attr['storage'])) abort(404);
        
        $toStorage = Storage::findOrFail($attr['storage']);
        if (! $request->file('file')) {
            if ($file->storage != $toStorage) dispatch(new DownloadFile($file, $toStorage));
        } else {
            if ($file->id) {
                $file->uploaded = false;
                $service = $file->storage->service;
                (new $service($file))->remove();
            }
            $filename = $request->file('file')->store('uploads');
            $file->size = $attr['file']->getClientSize();
            $file->uuid = $filename;
            $file->storage()->associate($toStorage);
            $file->owner()->associate($user);
            $file->subtask()->associate($subtask);
            $file->name = $attr['name'] ? ($attr['name']) : pathinfo($attr['file']->getClientOriginalName(), PATHINFO_FILENAME);
        }
        $file->name = $attr['name'] ? $attr['name'] : $file->name;
        $file->description = $attr['description'];
        $file->save();
        
        return $file;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Subtask $subtask)
    {   
        $this->authorize(File::class);
        $file = $this->assign($request, $subtask, new File);
        
        return json_encode($file->only('name', 'size', 'id', 'description'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Subtask $subtask, File $file)
    {
        $this->authorize($file);
        
        if (! $file->publicly_available_until) {
            $service = $file->storage->service;
            $service = new $service($file);
            $file->publicly_available_until = Carbon::now()->addSeconds(config('app.enablePublicLink'));
            $file->public_url = $service->link();
            $file->save();
        }
        return redirect()->away($file->public_url);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subtask $subtask, File $file)
    {   
        $this->authorize($file);
        $file = $this->assign($request, $subtask, $file);
        
        return json_encode($file->only('name', 'size', 'id', 'description'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Subtask $subtask, File $file)
    {
        $this->authorize($file);
        
        DeleteFile::dispatch($file);
        $file->delete();
    }
}
