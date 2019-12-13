<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use CommentableAndTaggableTrait;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = 'informations';
    protected $casts = [
        'owner_id' => 'integer',
    ];
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
