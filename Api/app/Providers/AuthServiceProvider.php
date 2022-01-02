<?php

namespace App\Providers;

use App\Models\Permissions;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Traits\TraitResponse;

class AuthServiceProvider extends ServiceProvider
{
    use TraitResponse;
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Permissions::where('parent_id', '>', '0')->get()->map(function ($permission) {
        //     Gate::define($permission->key_code, function (User $user) use ($permission) {
        //         $role = $user->role;
        //         $permissions = $role->permissions;
        //         if ($permissions->contains('key_code', $permission->key_code)) {
        //             return true;
        //         }
        //         return Response::deny('Bạn không có quyền truy cập !');
        //     });
        // });
    }
}