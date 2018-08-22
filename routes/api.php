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

Route::get('/', function(){
    return response()->json(['status' => 'OK', 'message' => 'API connected']);
});

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::group(['middleware' => 'jwt.auth'], function () {

    Route::resource('teacher', 'TeacherController', ['except' => [
        'store', 'index'
    ]]);

    Route::resource('student', 'StudentsController', ['except' => [
        'store', 'index'
    ]]);
    
    Route::post('student/request-teacher/{student}', 'StudentController@studentRequestTeacher');

    Route::post('teacher-accept-student/{teacher}', 'TeacherController@teacherAcepptStudent');
});