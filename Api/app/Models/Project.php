<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'hotline',
        'description',
        'address',
        'cycle_collect',
        'extension_time'
    ];

    protected $table = 'projects';
    protected $primaryKey = 'id';
    public function projectServices()
    {
        return $this->hasMany(ProjectService::class, 'project_id');
    }
    public function buildings()
    {
        return $this->hasMany(Building::class, 'project_id');
    }
    public function room_type()
    {
        return $this->hasMany(Room_type::class, 'project_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['project_id'] ?? false, function ($query, $id) {
            $query->where('id', $id);
        });
    }
}
