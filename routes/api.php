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

Route::post( 'login', 'Api\AuthJwtController@login' );
Route::post( 'register', 'Api\AuthJwtController@register');
Route::post( 'forgot', 'Api\UserController@forgotMail' );

Route::group(['middleware' => ['auth:api'], 'namespace' => 'Api\\'], function () {

	Route::get('users', 'UserController@index');
	Route::put('updateUser', 'UserController@update');

});

Route::group( [ 'middleware' => [ 'jwt.auth' ], 'namespace' => 'Api\\' ], function () {
	Route::post('user', 'AuthJwtController@me');
	Route::get('logout', 'AuthJwtController@logout');


} );
