<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'town_id'];
    protected $table = 'districts';
    protected $primaryKey = 'id';
    public function town(){
        return $this->belongsTo(District::class,'town_id');
    }
    public function wards(){
        return $this->hasMany(Ward::class,'district_id');
    }

}
