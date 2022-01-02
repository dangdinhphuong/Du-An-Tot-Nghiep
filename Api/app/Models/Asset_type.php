<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset_type extends Model
{
    use HasFactory;
    protected $table = 'asset_types';
    protected $fillable = ['id', 'name'];
    
    public function assets() {
        return $this->hasMany(Asset::class, 'asset_type_id');
    }
}
