<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $table = 'assets';
    protected $fillable = [
        'id', 'name', 'price', 'unit_asset_id ',
        'asset_type_id ', 'min_inventory', 'description',
        'image', 'producer_id '
    ];

    public function producer()
    {
        return $this->belongsTo(Producer::class, 'producer_id');
    }

    public function type_asset()
    {
        return $this->belongsTo(Asset_type::class, 'asset_type_id');
    }

    public function unit_asset()
    {
        return $this->belongsTo(Unit_asset::class, 'unit_asset_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? false, function ($query, $name) {
            $query->where('name', 'like', '%' . $name . '%');
        });
        $query->when($filters['asset_type_id'] ?? false, function ($query, $asset_type_id) {
            $query->whereHas('type_asset', function ($query) use ($asset_type_id) {
                $query->where('id', $asset_type_id);
            });
        });
    }
}
