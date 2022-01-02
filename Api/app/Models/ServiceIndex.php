<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceIndex extends Model
{
    use HasFactory;
    protected $table = 'service_indexes';
    protected $fillable = ['index_water', 'index_electric', 'room_id', 'note', 'img', 'status'];
    public $timestamps = false;
    // public function getCreatedAtAttribute()
    // {
    //     return date('m-y', strtotime($this->attributes['created_at']));
    // }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'service_index_id');
    }
    public function scopeDate($query, $date)
    {
        // dd($date);
        if (!is_null($date)) {
            return $query->where('created_at', 'like', '%' . $date . '%');
        }

        return $query;
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['project_id'] ?? false, function ($query, $project) {
            $query->whereHas('room.floor.building.project', function ($query) use ($project) {
                $query->where('id', $project);
            });
        });
        $query->when($filters['building_id'] ?? false, function ($query, $building) {
            $query->whereHas('room.floor.building', function ($query) use ($building) {
                $query->where('id', $building);
            });
        });
        $query->when($filters['floor_id'] ?? false, function ($query, $floor) {
            $query->whereHas('room.floor', function ($query) use ($floor) {
                $query->where('id', $floor);
            });
        });
        $query->when($filters['room_id'] ?? false, function ($query, $room) {
            $query->whereHas('room', function ($query) use ($room) {
                $query->where('id', $room);
            });
        });
        $query->when($filters['room_name'] ?? false, function ($query, $room_name) {
            $query->whereHas('room', function ($query) use ($room_name) {
                $query->where('name', 'like', '%' . $room_name . '%');
            });
        });
        $query->when(array_key_exists('status', $filters)  ?? false, function ($query) use ($filters) {
            $query->where('status', $filters['status']);
        });
        $query->when($filters['created_at'] ?? false, function ($query, $created_at) {
            $query->where('created_at', 'like', '%' . $created_at . '%');
        });
    }
}
