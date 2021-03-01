<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'Frontend\HomeController@index')->name('home');
Route::get('/home', 'Frontend\HomeController@index')->name('home');

Route::get('/admin/general', 'Admin\GeneralSettingsController@index')->name('admin.generalsetting');
Route::put('/admin/general/update/{generalsetting}', 'Admin\GeneralSettingsController@update')->name('admin.generalsetting.update');
Route::get('/admin/general/redirectBack', 'Admin\GeneralSettingsController@redirectBack')->name('admin.general.redirectBack');

Route::get('/admin/localization', 'Admin\LocalizationSettingsController@index')->name('admin.localizationsetting');
Route::put('/admin/localization/update/{localizationsetting}', 'Admin\LocalizationSettingsController@update')->name('admin.localizationsetting.update');
Route::get('/account', 'Frontend\AccountController@index')->name('account');
Route::get('/changepass', 'Frontend\AccountController@changepass')->name('changepass');
Route::put('/account/update', 'Frontend\AccountController@update')->name('account.update');
Route::put('/account/updatePassword', 'Frontend\AccountController@updatePassword')->name('account.updatePassword');

Route::get('/emailverifyowner', 'Auth\RegisterController@emailverifyowner')->name('emailverifyowner');
Route::get('/emailverifygiver', 'Auth\RegisterController@emailverifygiver')->name('emailverifygiver');

Route::POST('/sendverifycode', 'Auth\RegisterController@sendverifycode')->name('sendverifycode');
Route::get('/emailverifyforresend/{email}/{role}', 'Auth\RegisterController@emailverifyforresend')->name('emailverifyforresend');
Route::get('/directconfirmpage/{email}/{role}/{codes}', 'Auth\RegisterController@directconfirmpage')->name('directconfirmpage');
Route::post('/validatecode', 'Auth\RegisterController@validatecode')->name('validatecode');

Route::get('/signupasowner', 'Auth\RegisterController@signupasowner')->name('signupasowner');
Route::get('/signupasgiver', 'Auth\RegisterController@signupasgiver')->name('signupasgiver');


Route::resource('credentials', 'Admin\CredentialsController');
Route::get('/credentials', 'Admin\CredentialsController@index')->name('credentials.index');

Route::resource('admin/video', 'Admin\VideoController');
Route::get('/admin/video', 'Admin\VideoController@index')->name('video.index');

Route::resource('licenses', 'Admin\CaregivinglicensesController');
Route::get('/licenses', 'Admin\CaregivinglicensesController@index')->name('licenses.index');


Route::get('/users/resetpwd/{token}', 'Admin\UsersController@resetpwd')->name('users.resetpwd');
Route::POST('/users/resetUserpassword', 'Admin\UsersController@resetUserpassword')->name('users.resetUserpassword');