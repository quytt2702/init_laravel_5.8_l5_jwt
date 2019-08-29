<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class VerifyJWTAuthToken
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        try {
            $payload = auth()->payload();
            $passwordConfirm = $payload->get('password_changed_at');
            $http_host = $payload->get('http_host');
            $http_host_current = $_SERVER['HTTP_HOST'];

            // Check domain in token and current domain
            if ($http_host != $http_host_current) {
                auth()->logout();

                throw new AuthenticationException;
            }
            // Check password in token and password of user
            if ($passwordConfirm != auth()->user()->password_changed_at) {
                auth()->logout();

                return sendError(
                    trans('messages.auth.authenError'),
                    trans('messages.auth.errorPasswordChanged'),
                    401
                );
            }
        } catch (\Exception $e) {
            throw new AuthenticationException;
        }

        return $next($request);
    }
}
