<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostHistory extends Pivot
{
    protected $guarded = [];
    protected $casts = [
        'user_id' => 'integer',
        'post_id' => 'integer',
    ];
    
    public function getBeforeAttribute($value)
    {
        return json_decode($value);
    }
    
    public function getAfterAttribute($value)
    {
        return json_decode($value);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
