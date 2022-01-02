<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAnnounce extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected $table = 'type_announces';
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'type_announce_id');
    }
}
