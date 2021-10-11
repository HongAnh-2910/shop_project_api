<?php


namespace App\Repositories\Auth;

interface AuthRepositoryInterface
{
    function register($request);

    /**
     * @param $request
     *
     * @return mixed
     */
    function getUserForgetPassword($request);

    function getUserRestPassword($request);

}
