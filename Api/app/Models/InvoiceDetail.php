<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'invoice_details';
    protected $fillable = [
        'invoice_id',
        'room_fee_info',
        'project_service_info',
        'student_info',
        'room_info',
        'invoice_content',
        'created_at'
    ];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['project_id'] ?? false, function ($query, $project_id) {
            $query->whereHas('invoice.contract.room.floor.building.project', function ($query) use ($project_id) {
                $query->where('id', $project_id);
            });
        });
    }
}
