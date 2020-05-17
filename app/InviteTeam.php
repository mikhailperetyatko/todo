<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\TeamInvite;

class InviteTeam extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = 'invite_team';
    
    protected static function boot()
    {
        parent::boot();
        static::created(function(InviteTeam $invite){
            TeamInvite::dispatch($invite);
        });
    }
    
    public function getRouteKeyName()
    {
        return 'guid';
    }
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    
    public function refer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
