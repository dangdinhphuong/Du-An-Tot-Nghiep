<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'slug', 'district_id' ];
    protected $table = 'wards';
    protected $primaryKey = 'id';
    
    public function district(){
        return $this->belongsTo(Ward::class,'district_id');
    }
}
