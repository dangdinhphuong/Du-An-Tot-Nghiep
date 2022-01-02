<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Tasks extends Model
{
    use HasFactory;
    protected $table = 'tasks';
    protected $fillable = [
        'title',
        'date_start',
        'date_end',
        'priority',
        'desc',
        'user_create_id',
        'user_undertake_id',
        'processed',
        'status'
    ];
    //'title', 'date_start','date_end','priority'
    public function scopeFilter($query, array $filters)
    { 
        $query->when($filters['title'] ?? false, function ($query, $title) {
           $query->Where('title', 'LIKE', '%' . $title . '%');
        });

     if(isset($filters['date_start'])&&isset($filters['date_end'])){  
       if($filters['date_start']!=""||$filters['date_end']!=""){
            if($filters['date_start']==""){
                $filters['date_start']=$filters['date_end'];   
            }
            else if($filters['date_end']==""){
                $filters['date_end']=$filters['date_start']; 
            }
           // dd($filters['date_start'],$filters['date_end']);
            $query->where('date_start','>=',$filters['date_start'])->where('date_end','<=',$filters['date_end']);

       }}

        $query->when($filters['priority'] ?? false, function ($query, $priority) {
            $query->where('priority',$priority);
        });

    }
    public function user_create()
    {
        return $this->belongsTo(User::class,'user_create_id')->select('id', 'first_name', 'last_name',"email");
    }

    public function user_undertake()
    {
        return $this->belongsTo(User::class,'user_undertake_id')->select('id', 'first_name', 'last_name',"email");
    }
}