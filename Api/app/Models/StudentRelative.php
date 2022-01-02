<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRelative extends Model
{
    use HasFactory;
    protected $table = 'student_relatives';
    protected $guarded = [];
    public function studentInfo(){
        return $this->belongsTo(StudentInfo::class,'student_info_id');
    }
}
