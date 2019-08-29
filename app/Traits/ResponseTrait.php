<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;

trait ResponseTrait {
    /**
     * @param $message
     * @param int $status
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($message, $data = [], $status = 200)
    {
        $response = [
            'status'  => $status,
            'title' => $message,
        ];

        if(!empty($data)) {
            $response = array_merge($response, $data);
        }

        return response()->json($response, $status);
    }

    /**
     * @param $token
     * @param array $options
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponseToken($token, $options = [], $status = 200)
    {
        $response = [
            'status'  => $status,
            'title' => $options['message'] ?? trans('messages.auth.loginSuccess'),
            'data' => [
                'id' => $token,
                'type' => 'Token',
                'attributes' => [
                    'accessToken' => $token,
                    'tokenType' => 'Bearer',
                    'expiresIn' => $options['expiresIn'] ?? config('jwt.ttl'),
                ],
            ]
        ];

        return response()->json($response, $status);
    }

    public function sendErrorValidate(array $errors)
    {
        throw ValidationException::withMessages($errors);
    }

    /**
     * @param $title
     * @param null $detail
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($title, $detail = null, $code = 404)
    {
        $error = trans('messages.common.serverError');

        $response = [
            'status' => $code,
            'title'  => $error,
            'errors' => [],
        ];

        $response['errors'][] = [
            'title' => $title,
            'detail' => $detail ?? $title
        ];

        return response()->json($response, $code);
    }
}