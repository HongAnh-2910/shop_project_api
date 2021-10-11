<?php

namespace App\Repositories\Auth;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepositories implements AuthRepositoryInterface
 {
     protected $user;

     public function __construct(User $user)
     {
         $this->user = $user;
     }

    /**
     * @param $request
     *
     * @return mixed
     */

     public function register($request)
     {
           return $this->user::create([
             'name'     => $request->input('name'),
             'email'    => $request->input('email'),
             'phone'    => $request->input('phone'),
             'address'  => $request->input('address'),
             'password' => Hash::make($request->input('password')),

         ]);
     }

    /**
     * @param $request
     *
     * @return mixed
     */

     public function getUserForgetPassword($request)
     {
         return $this->user::where('email', '=', $request->input('email'))->first();
     }

    /**
     * @param $request
     *
     * @return mixed
     */

     public function getUserRestPassword($request)
     {
         return $this->user::where('remember_token', '=', $request->input('token'))->where('remember_token', '<>',
             null)->first();
     }

 }
