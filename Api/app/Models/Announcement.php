<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';
    protected $fillable = [
        'level',
        'title',
        'content',
        'user_id',
        'type_announce_id',
        'range',
        'date_start',
        'date_end',
    ];
    public function typeAnnounce()
    {
        return $this->belongsTo(TypeAnnounce::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? false, function ($query, $title) {
            $query->where('title', 'like', '%' . $title . '%');
        });
        $query->when($filters['type_announce_id'] ?? false, function ($query, $type_announce_id) {
            $query->whereHas('typeAnnounce', function ($query) use ($type_announce_id) {
                $query->where('id', $type_announce_id);
            });
        });
        $query->when($filters['level'] ?? false, function ($query, $level) {
            $query->where('level', $level);
        });
        $query->when($filters['first_name'] ?? false, function ($query, $first_name) {
            $query->whereHas('users', function ($query) use ($first_name) {
                $query->where('first_name', 'like', '%' . $first_name . '%');
            });
        });
    }
}
