<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostHistory extends Model
{
    protected $guarded = [];
    protected $casts = [
        'owner_id' => 'integer',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    
}
