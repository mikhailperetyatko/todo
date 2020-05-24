<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DebtorAccount extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    protected $casts = [
        'periods' => 'json',
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    
    public function responders()
    {
        return $this->hasMany(Debtor::class);
    }
}
