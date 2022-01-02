<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'total_area',
        'note',
        'project_id',
    ];

    protected $table = 'buildings';
    protected $primaryKey = 'id';
    protected $withCount = ['floors'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function floors()
    {
        return $this->hasMany(Floor::class, 'building_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['project_id'] ?? false, function ($query, $project_id) {
            $query->where('project_id', 'like', '%' . $project_id . '%');
        });
    }

    public function beds_total()
    {
        return Floor::with('floors.rooms.beds')->count();
    }

    public function beds_count_contract()
    {
        return $this->hasMany(Floor::class, 'building_id');
    }

    public function beds_count_not_contract()
    {
        return $this->hasMany(Floor::class, 'building_id');
    }
}
