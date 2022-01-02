<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectService extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected  $table = 'project_services';
    protected $fillable  = ['name', 'unit', 'unit_price', 'project_id', 'permanent'];
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function contract()
    {
        return $this->hasMany(Contract::class, 'project_service_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['project_id'] ?? false, function ($query, $projectId) {
            $query->whereHas('project', function ($query) use ($projectId) {
                $query->where('id', $projectId);
            });
        });
    }
}
