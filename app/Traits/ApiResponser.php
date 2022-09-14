<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }
    private function errorResponse($message, $code) : JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }
    protected function showAll(Collection $collection, $code = 200) : JsonResponse
    {
        return $this->successResponse(['data' => $collection], $code);
    }
    protected function showOne(Model $instance, $code = 200) : JsonResponse
    {
        return $this->successResponse(['data' => $instance], $code);
    }
}

?>