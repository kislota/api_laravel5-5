<?php

use Illuminate\Http\Request;

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


Route::post('login', 'Api\AuthController@login');
//Route::post('recover', 'Api\AuthController@recover');
//Route::post('register', 'Api\AuthController@register');

Route::group(['middleware' => 'jwt.auth', 'namespace' => 'Api\\'], function (){

    Route::get('logout', 'AuthController@logout');
    Route::get('authlogin', 'AuthController@authlogin');
});



//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
