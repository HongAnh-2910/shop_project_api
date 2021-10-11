<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    const STATUS_SUCCESS = 200;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_NOT_FOUND = 404;
    const STATUS_NOT_RESPONSE = 500;
    const STATUS_ERROR_WITH_MESSAGE = 400;

    /**
     * @param $data
     * @param $message
     * @param int $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($data, $message, $status = 200)
    {
        $response = [
            'status' => 'success',
        ];
        if ($data) {
            $response['data'] = $data;
        }
        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $status);
    }

    /**
     * @param $data
     * @param $message
     * @param int $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function responseError($data, $message, $status = 400)
    {
        $response = [
            'status' => 'error',
        ];
        if ($data) {
            $response['data'] = $data;
        }
        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $status);
    }
}
