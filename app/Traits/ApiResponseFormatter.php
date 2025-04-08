<?php

namespace App\Traits;

trait ApiResponseFormatter
{
    public function formatResponse($data, $message = null, $status = 200) {
        {
            return response()->json([
                'success' => $status>=200 && $status<300,
                'message' => $message,
                'data' => $data,
                'status' => $status
            ]);
        }
    }
}