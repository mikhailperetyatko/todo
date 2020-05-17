<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
    
    public function hasPermission(string $value) : bool
    {
        return (boolean) $this->permissions()->where('value', $value)->first();
    }
}
