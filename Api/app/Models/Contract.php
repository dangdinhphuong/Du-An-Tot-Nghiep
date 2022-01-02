<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Contract extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'start_day',
        'end_day',
        'price',
        'deposit',
        'room_id',
        'bed_id',
        'user_id',
        'project_service_id',
        'deposit_state'
    ];
    protected $table = 'contracts';
    public function bed()
    {
        return $this->hasOne(Bed::class, 'id', 'bed_id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // public function projectServices()
    // {
    //     return $this->hasMany(ProjectService::class, 'project_service_id');
    // }
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'contract_id');
    }
    public function receipt()
    {
        return $this->hasOne(Receipt::class, 'contract_id');
    }
    public function contractHistoryInvoices()
    {
        return $this->hasMany(ContractHistoryInvoice::class, 'contract_id');
    }
    public function historyRents()
    {
        return $this->hasMany(HistoryRent::class, 'contract_id');
    }
    public function bedHistories()
    {
        return $this->hasMany(Bed_History::class, 'contract_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['start_day'] ?? false, function ($query, $start_day) {
            $query->where('start_day', 'like', '%' . $start_day . '%');
        });
        $query->when($filters['end_day'] ?? false, function ($query, $end_day) {
            $query->where('end_day', 'like', '%' . $end_day . '%');
        });
        $query->when($filters['room_id'] ?? false, function ($query, $room_id) {
            $query->whereHas('room', function ($query) use ($room_id) {
                $query->where('id', $room_id);
            });
        });
        $query->when($filters['bed_id'] ?? false, function ($query, $bed_id) {
            $query->whereHas('bed', function ($query) use ($bed_id) {
                $query->where('id', $bed_id);
            });
        });
    }
    public function scopeFilterTrash($query, array $filters)
    {
        $query->when($filters['room_id'] ?? false, function ($query, $room_id) {
            $query->whereHas('room', function ($query) use ($room_id) {
                $query->where('id', $room_id);
            });
        });
        $query->when($filters['bed_id'] ?? false, function ($query, $bed_id) {
            $query->whereHas('bed', function ($query) use ($bed_id) {
                $query->where('id', $bed_id);
            });
        });
        $query->when($filters['start_day'] ?? false, function ($query, $start_day) {
            $query->where('start_day', 'like', '%' . $start_day . '%');
        });
        $query->when($filters['end_day'] ?? false, function ($query, $end_day) {
            $query->where('end_day', 'like', '%' . $end_day . '%');
        });
    }
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($contract) {
            $contract->contractHistoryInvoices()->delete();
            $contract->historyRents()->delete();
            $contract->bedHistories()->delete();
        });
    }
}
