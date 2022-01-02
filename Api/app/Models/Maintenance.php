<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    protected $table = 'maintenaces';
    protected $fillable = [
        'name', 'type', 'status', 'note', 'user_create_id', 'user_undertake_id', 'date_start', 'date_end', 'periodic', 'reminder'
    ];
    public function userCreate()
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }
    public function userUndertake()
    {
        return $this->belongsTo(User::class, 'user_undertake_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? false, function ($query, $name) {
            $query->where('name', 'like', '%' . $name . '%');
        });
        $query->when($filters['type'] ?? false, function ($query, $type) {
            $query->where('type', $type);
        });
        $query->when(array_key_exists('status', $filters)  ?? false, function ($query) use ($filters) {
            $query->where('status',  $filters['status']);
        });
        $query->when($filters['user_undertake_id'] ?? false, function ($query, $user) {
            $query->whereHas('userUndertake', function ($query) use ($user) {
                $query->where('id', $user);
            });
        });
    }
}
