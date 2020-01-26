<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CacheFlushableAfterDeletedModelTrait;
use App\CacheFlushableAfterCreatedModelTrait;
use App\CacheFlushableAfterUpdatedModelTrait;

class Information extends Model
{
    use CommentableAndTaggableTrait;
    use CacheFlushableAfterDeletedModelTrait;
    use CacheFlushableAfterCreatedModelTrait;
    use CacheFlushableAfterUpdatedModelTrait;
    
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
