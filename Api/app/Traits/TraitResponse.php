<?php

namespace App\Traits;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Arr;
use Exception;
use Illuminate\Http\JsonResponse;

trait TraitResponse
{
    public function responseApi($data, int $code = 200, $mess = ''): JsonResponse
    {
        $status = true;

        if (preg_match('/^[4|5].{2}$/', $code)) {
            $status = false;
        }
        return response()->json([
            'status' => $status,
            'code' => $code,
            'data' => $data,
            'message' => $mess
        ], $code);
    }
}
