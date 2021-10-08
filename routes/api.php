<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', 'api\JWTAuthController@register');
    Route::post('login', 'api\JWTAuthController@login');
    Route::post('logout', 'api\JWTAuthController@logout');
    Route::post('refresh', 'api\JWTAuthController@refresh');
    Route::get('profile', 'api\JWTAuthController@profile');
    Route::patch('change_password', 'api\JWTAuthController@changePassword');
    Route::post('forget_password', 'api\JWTAuthController@forgetPassword');
    Route::post('rest_password', 'api\JWTAuthController@restPassword');

});

    Route::resource('categorys', 'api\CategorysController');
    Route::post('categorys/update/{id}', 'api\CategorysController@update');
    Route::delete('categorys/delete/{id}', 'api\CategorysController@delete');
