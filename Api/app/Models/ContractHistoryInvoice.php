<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractHistoryInvoice extends Model
{
    use HasFactory;
    protected $table = 'contract_history_invoice';
    protected $fillable = ['contract_id', 'status', 'created_at'];

    public $timestamps = false;
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
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
            $query->whereHas('contract.room.floor.building.project', function ($query) use ($project) {
                $query->where('id', $project);
            });
        });
        $query->when($filters['building_id'] ?? false, function ($query, $building) {
            $query->whereHas('contract.room.floor.building', function ($query) use ($building) {
                $query->where('id', $building);
            });
        });
        $query->when($filters['floor_id'] ?? false, function ($query, $floor) {
            $query->whereHas('contract.room.floor', function ($query) use ($floor) {
                $query->where('id', $floor);
            });
        });
        $query->when($filters['contract_id'] ?? false, function ($query, $contract_id) {
            $query->whereHas('contract', function ($query) use ($contract_id) {
                $query->where('id', $contract_id);
            });
        });
        $query->when(array_key_exists('status', $filters)  ?? false, function ($query) use ($filters) {
            $query->where('status', $filters['status']);
            // dd($query->toSql());
        });
    }
}
