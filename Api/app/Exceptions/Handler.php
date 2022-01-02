<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Response;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Traits\TraitResponse;

class Handler extends ExceptionHandler
{
    use TraitResponse;

    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
     
        $this->renderable(function (TokenInvalidException $e, $request) {
            return $this->responseApi("", 401, 'Token ko hợp lệ');
        });
        $this->renderable(function (TokenExpiredException $e, $request) {
            return $this->responseApi("", 402, 'Token đã hết hạn');
        });
        $this->renderable(function (JWTException $e, $request) {
            return $this->responseApi("", 401, 'Ko phân tích được token');
        });
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return $this->responseApi("", 404, 'Trang không tồn tại');
            }
        });
    }
   
}
