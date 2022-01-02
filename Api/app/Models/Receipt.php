<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Receipt extends Model
{
    use HasFactory;
    protected $table = 'receipts';
    protected $with = ['receiptsReason', 'users'];
    protected $fillable = [
        'contract_id',
        'invoice_id',
        'collection_date',
        'user_id',
        'amount_of_money',
        'payment_type',
        'note',
        'receipt_reason_id',
    ];
    public function receiptsReason()
    {
        return $this->belongsTo(Receipts_reason::class, 'receipt_reason_id', 'id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['collection_date'] ?? false, function ($query, $collection_date) {
            $query->where('collection_date', 'like', '%' . $collection_date . '%');
        });
        $query->when($filters['payment_type'] ?? false, function ($query, $payment_type) {
            $query->where('payment_type', 'like', '%' . $payment_type . '%');
        });
        $query->when($filters['note'] ?? false, function ($query, $note) {
            $query->where('note', 'like', '%' . $note . '%');
        });
        $query->when($filters['name'] ?? false, function ($query, $name) {
            $query->whereHas('users', function ($query) use ($name) {
                $query->where(DB::raw('CONCAT_WS(" ",last_name,first_name)'), 'like', '%' . $name . '%');
            });
        });
        $query->when($filters['receipt_reason_id'] ?? false, function ($query, $receipt_reason_id) {
            $query->whereHas('receiptsReason', function ($query) use ($receipt_reason_id) {
                $query->where('id', $receipt_reason_id);
            });
        });
        $query->when($filters['project_id'] ?? false, function ($query, $project_id) {
            $query->whereHas('contract.room.floor.building.project', function ($query) use ($project_id) {
                $query->where('id', $project_id);
            });
        });
    }
}
