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

Route::get('/', 'HomeController@toApi');

Route::get('user/verify/{verification_code}', 'AuthController@verifyUser');
Route::get('teacher-accept-student/{answer}/{notificable}', 'TeacherController@teacherAcepptStudent');