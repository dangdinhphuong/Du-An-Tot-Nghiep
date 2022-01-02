<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed_History extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_id',
        'bed_id',
        'contract_id',
        'day_transfer',
    ];

    protected $table = 'bed_histories';
    protected $primaryKey = 'id';
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
}
