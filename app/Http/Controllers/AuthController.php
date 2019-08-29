<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Contracts\UserRepository;
use Hash;
use App\Http\Requests\ChangePasswordRequest;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * AuthController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->sendError(
                trans('messages.auth.authenError'),
                trans('messages.auth.loginFail'),
                Response::HTTP_UNAUTHORIZED
            );
        }

        $options = [
            'message' => trans('messages.auth.loginSuccess'),
            'expiresIn' => config('jwt.ttl')
        ];

        return $this->sendResponseToken($token, $options);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->sendResponse(trans('messages.auth.logoutSuccess'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = $this->userRepository->find(auth()->id());

        return $this->sendResponse(trans('messages.common.requestSuccess'), $user);
    }

    /**
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError(
                trans('messages.auth.changePasswordFail'),
                trans('messages.auth.currentPasswordNotMatch'),
                Response::HTTP_BAD_REQUEST
            );
        }

        $newToken = $this->userRepository->updatePassword($request->password);

        $options = [
            'message' => trans('messages.auth.changePasswordSuccess'),
            'expiresIn' => config('jwt.ttl')
        ];

        return $this->sendResponseToken($newToken, $options);
    }
}
