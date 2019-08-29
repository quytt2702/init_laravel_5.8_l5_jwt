<?php
/**
 * Global helpers file with misc functions
 *
 */
use Illuminate\Validation\ValidationException;

if (!function_exists('get_class_name')) {
    /**
     * @param $object
     * @return bool|string
     */
    function get_class_name($object)
    {
        $classname = get_class($object);
        if ($pos = strrpos($classname, '\\')) {
            return substr($classname, $pos + 1);
        }

        return $classname;
    }
}

if (!function_exists('sendResponse')) {
    /**
     * @param $message
     * @param array $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    function sendResponse($message, $data = [], $status = 200)
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
}

if (!function_exists('sendResponseToken')) {
    /**
     * @param $token
     * @param array $options
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    function sendResponseToken($token, $options = [], $status = 200)
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
}

if (!function_exists('sendErrorValidate')) {
    /**
     * @param array $errors
     * @throws ValidationException
     */
    function sendErrorValidate(array $errors)
    {
        throw ValidationException::withMessages($errors);
    }
}

if (!function_exists('sendError')) {
    /**
     * @param $title
     * @param null $detail
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function sendError($title, $detail = null, $code = 404)
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