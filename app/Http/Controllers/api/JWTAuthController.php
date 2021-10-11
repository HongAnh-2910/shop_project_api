<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateAuthRequest;
use App\Http\Requests\ValidateChangePasswordRequest;
use App\Http\Requests\ValidateForgetPass;
use App\Http\Requests\ValidateLoginRequest;
use App\Http\Requests\ValidateRestPassword;
use App\Mail\ForgetPassword;
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
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgetPassword', 'restPassword']]);
    }

    /**
     * Register a User.
     *
     * @return JsonResponse
     */
    public function register(ValidateAuthRequest $request)
    {
        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'phone'    => $request->input('phone'),
            'address'  => $request->input('address'),
            'password' => Hash::make($request->input('password')),

        ]);

        return response()->json([
            'message' => 'Bạn đã đăng ký tài khoản thành công',
            'user'    => $user
        ], 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(ValidateLoginRequest $request)
    {
        if (is_numeric($request->get('loginKey'))) {
            $info_login = ['phone' => $request->get('loginKey'), 'password' => $request->get('password')];
            if ( ! $token = auth()->attempt($info_login)) {
                return response()->json(['error' => 'Bạn nhập sai tài khoản hoặc mật khẩu'], 422);
            } else {
                $user    = Auth::user();
                $massage = 'Bạn đã đăng nhập thành công';

                return $this->createNewToken($token, $user, $massage);
            }

        } else if (filter_var($request->get('loginKey'), FILTER_VALIDATE_EMAIL)) {
            $info_login = ['email' => $request->get('loginKey'), 'password' => $request->get('password')];
            if ( ! $token = auth()->attempt($info_login)) {
                return response()->json(['error' => 'Bạn nhập sai tài khoản hoặc mật khẩu'], 422);
            } else {
                $user    = Auth::user();
                $massage = 'Bạn đã đăng nhập thành công';

                return $this->createNewToken($token, $user, $massage);
            }
        } else {
            return response()->json(['error' => 'Bạn nhập sai định dạng email'], 422);
        }
//        return ['username' => $request->get('email'), 'password'=>$request->get('password')];

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
        if (Hash::check($request->input('password_old'), Auth::user()->password)) {
            User::where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->input('password')),
            ]);

            return response()->json(['messages' => 'Bạn đã đổi mật khẩu thành công'], 201);
        } else {
            return response()->json(['error' => 'Nhập lại mật khẩu cũ không trùng khớp'], 422);
        }
    }

    /**
     * @param ValidateForgetPass $request
     *
     * @return JsonResponse
     */

    public function forgetPassword(ValidateForgetPass $request)
    {
        $user_check = User::where('email', '=', $request->input('email'))->first();
        $user_count = $user_check->count();
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
     * @param ValidateRestPassword $request
     *
     * @return JsonResponse
     */

    public function restPassword(ValidateRestPassword $request)
    {
        $rest_password = User::where('remember_token', '=', $request->input('token'))->where('remember_token', '<>',
            null)->first();
        if ($rest_password) {
            $update_password = User::where('id', $rest_password->id)->update([
                'password' => Hash::make($request->input('password'))
            ]);
            if ($update_password) {
                $remember_token = User::where('id', $rest_password->id)->update([
                    'remember_token' => null
                ]);

                return response()->json(['message', 'Bạn đã lấy lại mật khẩu thành công'], 201);
            }
        } else {
            return response()->json(['error', 'Bạn đã lấy lại mật khẩu thất bại'], 422);
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
        ], 201);
    }
}
