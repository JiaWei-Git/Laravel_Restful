<?php
namespace App\Traits;

trait ApiResponseTrait
{
    public function errorResponse($message, $status, $code = null) {
        $code = $code ?? $status; //$code為null時為status
        return response([
            'message' => $message,
            'code' => $code,
        ], $status);
    }
}
