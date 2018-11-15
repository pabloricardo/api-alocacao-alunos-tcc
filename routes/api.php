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

Route::get('/', "HomeController@home");

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::get('obter/professor/{professor}', 'TeacherController@show');

Route::post('professor/cadastrar', 'TeacherController@store');


Route::group(['middleware' => ['jwt-auth']], function () {

    Route::resource('teacher', 'TeacherController', ['except' => [
        'store', 'index', 'destroy'
    ]]);

    Route::resource('student', 'StudentsController', ['except' => [
        'store', 'index', 'destroy'
    ]]);

    Route::resource('area', 'AreaController', ['except' => [
        'destroy'
    ]]);
    
    Route::post('student/request-teacher/{student}', 'StudentsController@studentRequestTeacher');

    Route::post('teacher-accept-student/{teacher}', 'TeacherController@teacherAcepptStudent');
});
