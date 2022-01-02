<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function permissionsChilden()
    {
        return $this->hasMany(Permissions::class, "parent_id");
    }
    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'permissions_roles', 'permmission_id', 'role_id');
    }
        public function permissionsFather()
    {
        return $this->belongsTo(Permissions::class, "parent_id");
    }
}
