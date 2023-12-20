<?php

namespace App\Traits;

trait ApiTraits
{
    public function apiResponse($status, $message, $data = null)
    {
        return response([
            'status' => $status,
            'message' => $message,
            'data' => $data ?? [],

        ]);
    }
}
