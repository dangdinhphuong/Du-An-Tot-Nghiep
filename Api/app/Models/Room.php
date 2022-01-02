<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'room_type_id', 'floor_id'];
    // public function floors()
    // {
    //     return $this->belongsTo(Room::class, 'floor_id');
    // }
    // protected $with = ['floor'];
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
    public function room_type()
    {
        return $this->belongsTo(Room_type::class, 'room_type_id');
    }
    public function beds()
    {
        return $this->hasMany(Bed::class);
    }
    public function service_index()
    {
        return $this->hasMany(ServiceIndex::class, 'room_id');
    }
    public function contracts()
    {
        return $this->hasMany(Contract::class, 'room_id');
    }
    public function historyRents()
    {
        return $this->hasMany(HistoryRent::class, 'room_id');
    }
    public function roomHistoryInvoices()
    {
        return $this->hasMany(RoomHistoryInvoice::class, 'room_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['room_name'] ?? false, function ($query, $name) {
            $query->where('name', 'like', '%' . $name . '%');
        });
        $query->when($filters['room_id'] ?? false, function ($query, $roomId) {
            $query->where('id', $roomId);
            // dd($query->toSql());
        });
        $query->when($filters['floor_id'] ?? false, function ($query, $floor) {
            $query->whereHas('floor', function ($query) use ($floor) {
                $query->where('id', $floor);
            });
        });
        $query->when($filters['building_id'] ?? false, function ($query, $building) {
            $query->whereHas('floor.building', function ($query) use ($building) {
                $query->where('id', $building);
            });
        });
        $query->when($filters['project_id'] ?? false, function ($query, $project) {
            $query->whereHas('floor.building.project', function ($query) use ($project) {
                $query->where('id', $project);
            });
        });
        $query->when($filters['room_type_id'] ?? false, function ($query, $roomType) {
            $query->whereHas('room_type', function ($query) use ($roomType) {
                $query->where('id', $roomType);
            });
        });
        $query->when(array_key_exists('deposit_state', $filters)  ?? false, function ($query) use ($filters) {
            $query->whereHas('contracts', function ($query) use ($filters) {
                $query->where('deposit_state', 'like', '%' . $filters['deposit_state'] . '%');
            });
        });
        $query->when($filters['not_exists'] ?? false, function ($query, $lastName) {
            $query->doesntHave('contracts');
        });
        $query->when($filters['exists'] ?? false, function ($query, $lastName) {
            $query->has('contracts');
        });
        $query->when($filters['name'] ?? false, function ($query, $name) {
            $query->whereHas('contracts.user', function ($query) use ($name) {
                $query->where(DB::raw('CONCAT_WS(" ",last_name,first_name)'), 'like', '%' . $name . '%');
            });
        });
        $query->when($filters['email'] ?? false, function ($query, $email) {
            $query->whereHas('contracts.user', function ($query) use ($email) {
                $query->where('email', 'like', '%' . $email . '%');
            });
        });
        $query->when($filters['phone'] ?? false, function ($query, $phone) {
            $query->whereHas('contracts.user', function ($query) use ($phone) {
                $query->where('phone', 'like', '%' . $phone . '%');
            });
        });
        $query->when($filters['address'] ?? false, function ($query, $address) {
            $query->whereHas('contracts.user', function ($query) use ($address) {
                $query->where('address', 'like', '%' . $address . '%');
            });
        });
        $query->when($filters['start_day'] ?? false, function ($query, $startDay) {
            $query->whereHas('contracts', function ($query) use ($startDay) {
                $query->where('start_day', $startDay);
            });
        });
        $query->when($filters['end_day'] ?? false, function ($query, $endDay) {
            $query->whereHas('contracts', function ($query) use ($endDay) {
                $query->where('end_day', $endDay);
            });
        });
    }
    public function scopeServiceIndex($query, array $filters)
    {
        $query->when($filters['month_year'] ?? false, function ($query, $month_year) {
            $query->whereHas('service_index', function ($query) use ($month_year) {
                // $dtime = Carbon::createFromFormat("Y-m-d", $month_year)->toDateTimeString();
                // $timeStamp = $dtime->getTimestamp();
                $modInsertDate = date('Y-m-d H:i:s', strtotime($month_year));
                // dd($timeStamp);
                // dd($modInsertDate);
                $query->where('created_at', $modInsertDate);
            });
        });
    }
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($room) {
            $room->historyRents()->delete();
            $room->roomHistoryInvoices()->delete();
        });
    }
}
