<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipts_reason extends Model
{
    use HasFactory;
    protected $table = 'receipt_reasons';
    protected $fillable = [
        'title',
        'description'
    ];
    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'receipt_reason_id');
    }
}
