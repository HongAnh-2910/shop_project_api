<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateAuthRequest;
use App\Http\Requests\ValidateChangePasswordRequest;
use App\Http\Requests\ValidateForgetPass;
use App\Http\Requests\ValidateLoginRequest;
use App\Http\Requests\ValidateRestPassword;
use App\Mail\ForgetPassword;
use App\Repositories\Auth\AuthRepositories;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Validator;
use App\User;


class JWTAuthController extends Controller
{
    protected $auth_repositories;
    protected $auth_service;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthRepositories $auth_repositories , AuthService $auth_service)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgetPassword', 'restPassword']]);
        $this->auth_repositories = $auth_repositories;
        $this->auth_service = $auth_service;
    }

    /**
     * Register a User.
     *
     * @return JsonResponse
     */

    public function register(ValidateAuthRequest $request)
    {
        $user = $this->auth_repositories->register($request);
        return $this->auth_service->register($user);

    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(ValidateLoginRequest $request)
    {
        return $this->auth_service->login($request);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function profile()
    {
        return response()->json(auth()->user());
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Bạn đã đăng xuất thành công'], 201);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * @param ValidateChangePasswordRequest $request
     *
     * @return JsonResponse
     */

    public function changePassword(ValidateChangePasswordRequest $request)
    {
        return $this->auth_service->changePassword($request);
    }

    /**
     * @param ValidateForgetPass $request
     *
     * @return JsonResponse
     */

    public function forgetPassword(ValidateForgetPass $request)
    {
        $user_check = $this->auth_repositories->getUserForgetPassword($request);
        $user_count = $user_check->count();
        return $this->auth_service->forgetPassword($user_check , $user_count , $request );
    }

    /**
     * @param ValidateRestPassword $request
     *
     * @return JsonResponse
     */

    public function restPassword(ValidateRestPassword $request)
    {
        $rest_password = $this->auth_repositories->getUserRestPassword($request);
        return $this->auth_service->restPassword($rest_password , $request);

    }


}
