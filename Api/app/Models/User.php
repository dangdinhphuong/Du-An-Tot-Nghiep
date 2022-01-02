<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Roles;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable  implements JWTSubject
{
    use HasFactory, Notifiable;
    use SoftDeletes;



    protected $fillable = [
        'first_name',
        'last_name',
        'password',
        'birth',
        'birth_place',
        'gender',
        'email',
        'address',
        'phone',
        'user_type',
        'role_id',
        'remember_token',
        'status'
    ];

    protected $with = ['studentInfo'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // $this->load('role',"studentInfo");

    public function role()
    {
        return $this->belongsTo(Roles::class);
    }

    public function studentInfo()
    {
        return $this->hasOne(StudentInfo::class, 'user_id');
    }
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'user_id');
    }
    public function contract()
    {
        return $this->hasMany(Contract::class, 'user_id');
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function historyRents()
    {
        return $this->hasMany(HistoryRent::class, 'user_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['id'] ?? false, function ($query, $id) {
            $query->where('id', $id);
        });
        $query->when($filters['CCCD'] ?? false, function ($query, $CCCD) {
            $query->whereHas('studentInfo', function ($query) use ($CCCD) {
                $query->where('cccd', $CCCD);
            });
        });
        $query->when($filters['student_code'] ?? false, function ($query, $student_code) {
            $query->whereHas('studentInfo', function ($query) use ($student_code) {
                $query->where('student_code', 'like', '%' . $student_code . '%');
            });
        });
        $query->when($filters['user_type'] ?? false, function ($query, $user_type) {
            $query->where('user_type', $user_type);
        });

        $query->when(array_key_exists('status', $filters) ?? false, function ($query) use ($filters) {
            $query->where('status', $filters['status']);
        });
        $query->when($filters['name'] ?? false, function ($query, $name) {
            $query->where(DB::raw('CONCAT_WS(" ",last_name,first_name)'), 'like', '%' . $name . '%');
        });
        $query->when($filters['email'] ?? false, function ($query, $email) {
            $query->where('email', 'like', '%' . $email . '%');
        });
        $query->when($filters['phone'] ?? false, function ($query, $phone) {
            $query->where('phone', 'like', '%' . $phone . '%');
        });
        $query->when(array_key_exists('not_exists', $filters) ?? false, function ($query) use ($filters) {
            $query->doesntHave('contract');
        });
        $query->when($filters['role_name'] ?? false, function ($query, $role_name) {
            $query->whereHas('role', function ($query) use ($role_name) {
                $query->where('name', $role_name);
            });
        });
        $query->when($filters['role_id'] ?? false, function ($query, $role_id) {
            $query->whereHas('role', function ($query) use ($role_id) {
                $query->where('id', $role_id);
            });
        });
    }
    public function getUsersTypeAttribute()
    {
        $users_Type = config("User.user_Type." . $this->attributes['user_type']);
        return $users_Type;
    }
    public function getStatusedAttribute()
    {
        $statused = config("User.action_status." . $this->attributes['status']);
        return $statused;
    }
}
