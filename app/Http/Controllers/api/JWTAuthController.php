<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateAuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ( ! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Bạn nhập sai tài khoản hoặc mật khẩu'], 401);
        } else {
            $user    = Auth::user();
            $massage = 'Bạn đã đăng nhập thành công';

            return $this->createNewToken($token, $user, $massage);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return response()->json(auth()->user());
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Bạn đã đăng xuất thành công'] ,201);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * @param Request $request
     */

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password_old'          => 'required',
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        if (Hash::check($request->input('password_old'), Auth::user()->password)) {
            User::where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->input('password')),
            ]);

            return response()->json(['messages' => 'Bạn đã đổi mật khẩu thành công'],201);
        } else {
            return response()->json(['error' => 'Nhập lại mật khẩu cũ không trùng khớp'],422);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token, $user, $message)
    {
        return response()->json([
            'access_token' => $token,
            'user'         => $user,
            'message'      => $message,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ] ,201);
    }
}
