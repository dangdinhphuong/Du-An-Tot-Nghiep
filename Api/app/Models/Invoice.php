<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'invoices';
    protected $fillable = [
        'contract_id',
        'date_payment',
        'student_id',
        'staff_id',
        'payment_type',
        'note',
        'total_money',
        'status',
        'created_at'
    ];

    use SoftDeletes;

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
    public function serviceIndexes()
    {
        return $this->belongsTo(ServiceIndex::class, 'service_index_id');
    }
    public function projectServices()
    {
        return $this->belongsTo(ProjectService::class, 'project_service_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function invoiceDetail()
    {
        return $this->hasOne(InvoiceDetail::class, 'invoice_id');
    }
}
