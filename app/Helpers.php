<?php

if (!function_exists('res_data')) {
    function res_data($data = [], $message = '', $status = 200)
    {
        return response()->json([
            'status' => in_array($status, [200, 201, 202, 203]) ? 'success' : 'error',
            'message' => $message,
            'result' => $data
        ], $status);
    }
}
