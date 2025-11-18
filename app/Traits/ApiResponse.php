<?php

namespace App\Traits;

trait ApiResponse
{
    public function apiSuccess($message,$data, $code){
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ],$code);
    }

    public function apiError($message,$data,$code)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data
        ],$code);
    }
}
