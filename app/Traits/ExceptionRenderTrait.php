<?php

namespace App\Traits;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;

trait ExceptionRenderTrait
{

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $data = [
            'status' => $e instanceof HttpException ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR,
            'title' => 'Internal Server Error',
            'errors' => [[
                'title' => trans('messages.common.serverError'),
                'detail' => $e->getMessage()?:('An exception of '.get_class_name($e)),
            ]]
        ];

        if ($e instanceof AuthorizationException) {
            $data = array_merge($data, [
                'status' => Response::HTTP_FORBIDDEN,
                'errors' => [[
                    'title' => trans('messages.common.permissionDenied'),
                    'detail' => $e->getMessage(),
                ]]
            ]);
        }

        if ($e instanceof UnauthorizedException || $e instanceof AuthenticationException) {
            $data = array_merge($data, [
                'status' => Response::HTTP_UNAUTHORIZED,
                'errors' => [[
                    'title' => trans('messages.auth.authenError'),
                    'detail' => $e->getMessage() ?: trans('messages.common.unauthorized'),
                ]]
            ]);
        }

        if ($e instanceof ModelNotFoundException) {
            $data = array_merge($data, [
                'status' => Response::HTTP_NOT_FOUND,
                'errors' => [[
                    'title' => trans('messages.common.notFoundError'),
                    'detail' => trans('messages.common.notFoundModel'),
                ]]
            ]);
        }
        if ($e instanceof HttpException) {
            $data = array_merge($data, [
                'status' => $e->getStatusCode(),
                'errors' => [[
                    'title' => trans('messages.common.notFoundError'),
                    'detail' => $e->getMessage()?:('An exception of '.get_class_name($e)),
                ]]
            ]);
        }

        if ($e instanceof HttpResponseException) {
            $data = array_merge($data, [
                'status' => $e->getResponse()->status(),
                'title' => trans('messages.common.validationError'),
            ]);

            $errorResponses = function ($errors) use ($data) {
                $errorResponses = [];
                foreach ($errors as $key => $error) {
                    if (!is_array($error)) {
                        $errorResponses[] = [
                            'title'     => trans('messages.common.badRequest'),
                            'detail'    => $error,
                        ];
                    } else {
                        foreach ($error as $detail) {
                            $errorResponses[] = [
                                'title'     => $data['title'],
                                'detail'    => $detail,
                                'source' => [
                                    'pointer' => $key
                                ]
                            ];
                        }
                    }
                }
                return $errorResponses;
            };
            $data['errors'] = $errorResponses((array)$e->getResponse()->getData());
        }

        if ($e instanceof ValidationException) {
            $data = array_merge($data, [
                'status' => Response::HTTP_BAD_REQUEST,
                'title' => trans('messages.common.validationError'),
            ]);

//            dd($e->validator->errors()->toArray());

            $errorResponses = function ($errors) use ($data) {
                $errorResponses = [];
                foreach ($errors as $key => $error) {
                    if (!is_array($error)) {
                        $errorResponses[] = [
                            'title'     => trans('messages.common.badRequest'),
                            'detail'    => $error,
                        ];
                    } else {
                        foreach ($error as $detail) {
                            $errorResponses[] = [
                                'title'     => $data['title'],
                                'detail'    => $detail,
                                'source' => [
                                    'pointer' => $key
                                ]
                            ];
                        }
                    }
                }
                return $errorResponses;
            };
            $data['errors'] = $errorResponses($e->validator->errors()->toArray());
        }
        return response()->json($data, $data['status']);
    }
}
