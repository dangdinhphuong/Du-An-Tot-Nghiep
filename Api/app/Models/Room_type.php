<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room_type extends Model
{
    use HasFactory;
    protected $table = 'room_types';
    protected $fillable = ['name', 'price', 'price_deposit', 'number_bed', 'project_id'];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_type_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['project_id'] ?? false, function ($query, $projectId) {
            $query->whereHas('project', function ($query) use ($projectId) {
                $query->where('id', $projectId);
            });
        });
    }
}
