<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producer extends Model
{
    use HasFactory;
    protected $table = 'producers';
    protected $fillable = ['id', 'name'];
    
    public function assets() {
        return $this->hasMany(Asset::class, 'producer_id');
    }
}
