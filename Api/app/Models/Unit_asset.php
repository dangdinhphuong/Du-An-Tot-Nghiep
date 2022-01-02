<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit_asset extends Model
{
    use HasFactory;
    protected $table = 'unit_assets';
    protected $fillable = ['id', 'name'];

    public function Assets() {
        return $this->hasMany(Asset::class, 'unit_asset_id');
    }
}
