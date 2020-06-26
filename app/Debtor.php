<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Debtor extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'father_id', 'mother_id', 'debtor_type_id', 'debtor_account_id'];
    
    protected $casts = [
        'residencePeriods' => 'json',
        'ownership' => 'json',
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'property_at',
        'birthday',
        'deathday',
    ];
    
    public function father()
    {
        return $this->belongsTo(Debtor::class, 'father_id');
    }
    
    public function mother()
    {
        return $this->belongsTo(Debtor::class, 'mother_id');
    }
    
    public function type()
    {
        return $this->belongsTo(DebtorType::class, 'debtor_type_id');
    }
    
    public function account()
    {
        return $this->belongsTo(DebtorAccount::class, 'debtor_account_id');
    }
    
    public function getResponsibilityAttribute()
    {
        if (strpos($this->property_size, '/')) {
            $result = explode('/', $this->property_size);
            return $result[0] / $result[1];
        } else {
            floatval($this->property_size);
        }
    }
}
