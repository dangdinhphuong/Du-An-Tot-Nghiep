<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentInteract extends Model
{
    use HasFactory;
    protected $table = 'student_interacts';
    protected $fillable = [
        'request_type',
        'content',
        'status',
        'date_send',
        'student_id',
        'staff_id',
        'check'
    ];
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id')->select('id', 'first_name', 'last_name', "email");
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id')->select('id', 'first_name', 'last_name', "email");
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['request_type'] ?? false, function ($query, $request_type) {
            $query->where('request_type', $request_type);
        });
        $query->when($filters['status'] ?? false, function ($query, $status) {
            $query->where('status', $status);
        });

        $query->when($filters['student_name'] ?? false, function ($query, $name) {
            $query->whereHas('student', function ($query) use ($name) {
                $query->where(DB::raw('CONCAT_WS(" ",last_name,first_name)'), 'like', '%' . $name . '%');
            });
        });
    }
}
