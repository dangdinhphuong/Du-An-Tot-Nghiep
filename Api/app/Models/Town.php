<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'slug'];
    protected $table = 'towns';
    protected $primaryKey = 'id';
    public function districts(){
        return $this->hasMany(District::class,'town_id');
    }
}
