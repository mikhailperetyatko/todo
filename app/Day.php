<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'owner_id'];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'date',
    ];
    protected $casts = [
        'is_weekend' => 'boolean',
    ];
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
