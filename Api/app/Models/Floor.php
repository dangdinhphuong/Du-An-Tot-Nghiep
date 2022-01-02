<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Floor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'total_area',
        'building_id',
    ];

    protected $table = 'floors';
    protected $primaryKey = 'id';
    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }
    public function rooms()
    {
        return $this->hasMany(Room::class, 'floor_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? false, function ($query, $name) {
            $query->where('name', 'like', '%' . $name . '%');
        });

        $query->when($filters['building_id'] ?? false, function ($query, $user) {
            $query->whereHas('building', function ($query) use ($user) {
                $query->where('id', $user);
            });
        });
    }
}
