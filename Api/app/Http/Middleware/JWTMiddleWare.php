<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use App\Models\User;
use App\Traits\TraitResponse;

class JWTMiddleWare
{
    use TraitResponse;
    public function handle(Request $request, Closure $next)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $User = User::find($user->id);

        if ($User->status == 1 || $User->status == "1") {
            return $next($request);
        }
        auth()->logout();
        return $this->responseApi("", 200, "Tài khoản đã bị vô hiệu hóa !");
    }
}
