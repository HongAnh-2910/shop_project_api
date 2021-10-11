<?php

namespace App\Services;

use App\Helper\StatusJson;
use App\Mail\ForgetPassword;
use App\Repositories\Auth\AuthRepositories;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthService
{
    protected $auth_repositories;
    protected $user;

    /**
     * AuthService constructor.
     *
     * @param AuthRepositories $auth_repositories
     * @param User $user
     */
    public function __construct(AuthRepositories $auth_repositories, User $user)
    {
        $this->auth_repositories = $auth_repositories;
        $this->user              = $user;
    }

    public function register($user)
    {
        if ($user) {
            return response()->json([
                'message' => 'Bạn đã đăng ký tài khoản thành công',
                'user'    => $user
            ], StatusJson::STATUS_SUCCESS);
        }
    }

    /**
     * @param $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login($request)
    {
        if (is_numeric($request->input('loginKey'))) {
            $info_login = ['phone' => $request->input('loginKey'), 'password' => $request->input('password')];
            if ( ! $token = auth()->attempt($info_login)) {
                return response()->json(['error' => 'Bạn nhập sai tài khoản hoặc mật khẩu'], StatusJson::STATUS_ERROR);
            } else {
                $user    = Auth::user();
                $massage = 'Bạn đã đăng nhập thành công';

                return $this->createNewToken($token, $user, $massage);
            }

        } else if (filter_var($request->input('loginKey'), FILTER_VALIDATE_EMAIL)) {
            $info_login = ['email' => $request->input('loginKey'), 'password' => $request->input('password')];
            if ( ! $token = auth()->attempt($info_login)) {
                return response()->json(['error' => 'Bạn nhập sai tài khoản hoặc mật khẩu'], StatusJson::STATUS_ERROR);
            } else {
                $user    = Auth::user();
                $massage = 'Bạn đã đăng nhập thành công';

                return $this->createNewToken($token, $user, $massage);
            }
        } else {
            return response()->json(['error' => 'Bạn nhập sai định dạng email'], StatusJson::STATUS_ERROR);
        }
    }

    /**
     * @param $request
     *
     * @return JsonResponse
     */

    public function changePassword($request)
    {
        if (Hash::check($request->input('password_old'), Auth::user()->password)) {
            User::where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->input('password')),
            ]);

            return response()->json(['messages' => 'Bạn đã đổi mật khẩu thành công'], StatusJson::STATUS_SUCCESS);
        } else {
            return response()->json(['error' => 'Nhập lại mật khẩu cũ không trùng khớp'], StatusJson::STATUS_ERROR);
        }
    }

    /**
     * @param $user_check
     * @param $user_count
     * @param $request
     *
     * @return JsonResponse
     */

    public function forgetPassword($user_check, $user_count, $request)
    {
        if ($user_count == 0) {
            return response()->json(['message' => 'Email chưa được đăng ký'], 422);
        } else {
            $str_random = Str::random(60);
            User::where('id', $user_check->id)->update([
                'remember_token' => $str_random,
            ]);
            $link_rest_pass = url('/update_new_pass?email=' . $request->input('email') . '&token=' . $str_random);
            $data           = array($link_rest_pass);
            Mail::to($request->input('email'))->send(new ForgetPassword($data));
        }
    }

    /**
     * @param $rest_password
     * @param $request
     *
     * @return JsonResponse
     */

    public function restPassword($rest_password, $request)
    {
        if ($rest_password) {
            $update_password = $this->user::where('id', $rest_password->id)->update([
                'password' => Hash::make($request->input('password'))
            ]);
            if ($update_password) {
                $this->user::where('id', $rest_password->id)->update([
                    'remember_token' => null
                ]);

                return response()->json(['message' => 'Bạn đã lấy lại mật khẩu thành công'], 201);
            }
        } else {
            return response()->json(['error'=> 'Bạn đã lấy lại mật khẩu thất bại'], 422);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */

    protected function createNewToken($token, $user, $message)
    {
        return response()->json([
            'access_token' => $token,
            'user'         => $user,
            'message'      => $message,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ], StatusJson::STATUS_SUCCESS);
    }
}
