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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// user email verify and register, login and logout by mobile iOS
Route::POST('/v1/emailverify', 'Admin\UsersController@emailverify');
Route::POST('/v1/validateCode', 'Admin\UsersController@validateCode');
Route::POST('/v1/register', 'Admin\UsersController@store');
Route::POST('/v1/loginwithApple', 'Admin\UsersController@loginUserwithApple');
Route::POST('/v1/login', 'Admin\UsersController@loginUser');
Route::POST('/v1/logout', 'Admin\UsersController@logout');