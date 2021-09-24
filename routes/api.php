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
Route::POST('/v1/loginwithGoogle', 'Admin\UsersController@loginUserwithGoogle');
Route::POST('/v1/loginUserwithFacebook', 'Admin\UsersController@loginUserwithFacebook');
Route::POST('/v1/login', 'Admin\UsersController@loginUser');
Route::POST('/v1/logout', 'Admin\UsersController@logout');

Route::POST('/v1/uploadCredentialFile', 'Admin\UsersController@uploadCredentialFile');
Route::POST('/v1/deleteCredentialuser', 'Admin\UsersController@deleteCredentialuser');

Route::POST('/v1/forgotpassword', 'Admin\UsersController@forgotpassword');
Route::POST('/v1/skills', 'Admin\UsersController@saveSkillandhobby');
Route::GET('/v1/getcredentials', 'Admin\UsersController@getCredentials');
Route::GET('/v1/getLicenses', 'Admin\UsersController@getLicenses');
Route::POST('/v1/updateAccount', 'Admin\UsersController@updateAccount');
Route::POST('/v1/addCredential', 'Admin\UsersController@addCredential');

Route::GET('/v1/getvideolink', 'Admin\UsersController@getvideolink');

Route::POST('/v1/deleteExtracredential', 'Admin\UsersController@deleteExtracredential');
Route::POST('/v1/deleteAccount', 'Admin\UsersController@deleteAccount');

Route::POST('/v1/sendSMS', 'Frontend\HomeController@sendSMS')->name('home.sendSMS');


Route::GET('/v1/getUserswithLookingjob', 'Admin\UsersController@getUserswithLookingjob');