<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model
{
    use HasFactory;
    protected $table = 'student_infos';
    protected $with = ['studentRelative'];

    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function studentRelative()
    {
        return $this->hasOne(StudentRelative::class);
    }
}
