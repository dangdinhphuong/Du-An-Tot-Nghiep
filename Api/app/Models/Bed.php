<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bed extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'room_id',
    ];

    protected $table = 'beds';
    protected $primaryKey = 'id';
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function contract()
    {
        return $this->hasOne(Contract::class, 'bed_id');
    }
    public function scopeStudent($query, $studentId)
    {
        // dd($date);
        if (!is_null($studentId)) {
            return
                $query->whereHas('contract', function ($query) use ($studentId) {
                    $query->where('user_id', $studentId);
                });
        }

        return $query;
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['room_id'] ?? false, function ($query, $room_id) {
            $query->whereHas('room', function ($query) use ($room_id) {
                $query->where('id', $room_id);
            });
        });
        $query->when($filters['room_name'] ?? false, function ($query, $name) {
            $query->whereHas('room', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        });
        $query->when($filters['floor_id'] ?? false, function ($query, $floor) {
            $query->whereHas('room.floor', function ($query) use ($floor) {
                $query->where('id', $floor);
            });
        });
        $query->when($filters['building_id'] ?? false, function ($query, $building) {
            $query->whereHas('room.floor.building', function ($query) use ($building) {
                $query->where('id', $building);
            });
        });
        $query->when($filters['project_id'] ?? false, function ($query, $project) {
            $query->whereHas('room.floor.building.project', function ($query) use ($project) {
                $query->where('id', $project);
            });
        });
        $query->when($filters['room_type_id'] ?? false, function ($query, $roomType) {
            $query->whereHas('room.room_type', function ($query) use ($roomType) {
                $query->where('id', $roomType);
            });
        });
        $query->when(array_key_exists('deposit_state', $filters)  ?? false, function ($query) use ($filters) {
            $query->whereHas('contract', function ($query) use ($filters) {
                $query->where('deposit_state', 'like', '%' . $filters['deposit_state'] . '%');
            });
            // dd($query->toSql());
        });
        $query->when($filters['not_exists'] ?? false, function ($query, $lastName) {
            $query->doesntHave('contract');
        });
        $query->when($filters['exists'] ?? false, function ($query, $lastName) {
            $query->has('contract');
        });
        $query->when($filters['last_name'] ?? false, function ($query, $lastName) {
            $query->whereHas('contract.user', function ($query) use ($lastName) {
                $query->where('last_name', 'like', '%' . $lastName . '%');
            });
        });
        $query->when($filters['first_name'] ?? false, function ($query, $firstName) {
            $query->whereHas('contract.user', function ($query) use ($firstName) {
                $query->where('first_name', 'like', '%' . $firstName . '%');
            });
        });
        $query->when($filters['email'] ?? false, function ($query, $email) {
            $query->whereHas('contract.user', function ($query) use ($email) {
                $query->where('email', 'like', '%' . $email . '%');
            });
        });
        $query->when($filters['user_id'] ?? false, function ($query, $id) {
            $query->whereHas('contract.user', function ($query) use ($id) {
                $query->where('id', $id);
            });
        });
        $query->when($filters['phone'] ?? false, function ($query, $phone) {
            $query->whereHas('contract.user', function ($query) use ($phone) {
                $query->where('phone', 'like', '%' . $phone . '%');
            });
        });
        $query->when($filters['address'] ?? false, function ($query, $address) {
            $query->whereHas('contract.user', function ($query) use ($address) {
                $query->where('address', 'like', '%' . $address . '%');
            });
        });
        $query->when($filters['start_day'] ?? false, function ($query, $startDay) {
            $query->whereHas('contract', function ($query) use ($startDay) {
                $query->where('start_day', $startDay);
            });
        });
        $query->when($filters['end_day'] ?? false, function ($query, $endDay) {
            $query->whereHas('contract', function ($query) use ($endDay) {
                $query->where('end_day', $endDay);
            });
        });
    }
}
