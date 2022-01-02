<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryRent extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_id',
        'user_id',
        'contract_id',
        'date_rent',
        'state',
    ];
    public $timestamps = false;
    protected $table = 'history_rents';
    protected $primaryKey = 'id';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
