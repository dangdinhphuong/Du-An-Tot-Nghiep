<?php

namespace App\Models;

use App\Models\Permissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'roles';
    public function permissions()
    {
        return $this->belongsToMany(Permissions::class, 'permissions_roles', 'role_id', 'permmission_id');
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
