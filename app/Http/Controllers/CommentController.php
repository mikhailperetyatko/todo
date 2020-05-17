<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Subtask;
use App\File;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected function getValidateRules()
    {
        return [
            'body' => 'string|min:5|max:65535|required',
            'refer_comment' => 'integer|nullable',
            'files.*' => 'integer|nullable',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Subtask $subtask)
    {
        if (policy(Comment::class)->index(auth()->user(), $subtask)) return json_encode([
            'comments' => $subtask->comments()->with('owner', 'referComment', 'files')->orderBy('created_at', 'desc')->simplePaginate(config('app.paginate_limit')),
        ]);
        else abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Subtask $subtask)
    {
        $user = auth()->user();
        if (policy(Comment::class)->create($user, $subtask)) {
            $attr = $request->validate($this->getValidateRules());
            $comment = new Comment;
            $comment->subtask()->associate($subtask);
            $comment->owner()->associate($user);
            
            $files = $attr['files'] ?? [];
            unset($attr['files']);
            $comment->fill($attr);
            $comment->save();
            
            File::syncFilesWithModel($comment, $files);
            
            return json_encode(['result' => true]);
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Subtask $subtask, string $comment)
    {
        $comment = Comment::with('owner')->findOrFail((int) $comment)->with('files');
        $this->authorize($comment);
        
        return json_encode([
            'comment' => $comment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Subtask $subtask, Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subtask $subtask, Comment $comment)
    {
        $this->authorize($comment);
        $attr = $request->validate($this->getValidateRules());
        
        $comment->update([
            'body' => $attr['body'],
        ]);
        
        File::syncFilesWithModel($comment, $attr['files'] ?? []);
        
        return json_encode(['result' => true]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
