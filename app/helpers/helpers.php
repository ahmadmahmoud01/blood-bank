<?php

namespace App\helpers;


function apiResponse($status, $message, $data = null, $statusCode = 200) {
    return response()->json([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ], $statusCode);
}
