<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DebtorAccount extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    protected $casts = [
        'balance' => 'json',
        'payments_relation' => 'json',
        'claim_calculation' => 'json',
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
