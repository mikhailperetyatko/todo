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
        return $this->convertAllBooleanToCharInJson($value);
    }
    
    public function getAfterAttribute($value)
    {
        return $this->convertAllBooleanToCharInJson($value);
    }
    
    public function boolToChar(bool $value)
    {
        return $value ? 'on' : 'off';
    }
    
    public function convertAllBooleanToCharInJson(string $value)
    {
        $value = json_decode($value);
        foreach ($value as $key => $item) {
            if (gettype($item) == 'boolean') $value->$key = $this->boolToChar($item);
        }
        return $value;
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
