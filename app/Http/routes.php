<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// проверить логирование и смену на сервере

use App\User;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
	if(Auth::guest())return redirect('/login');
	else return redirect('/teacher');
});

Route::get('/login', 'Auth\AuthController@getLogin');
Route::post('/login', 'Auth\AuthController@postLogin');
Route::get('/logout', 'Auth\AuthController@getLogout');

Route::group(['middleware' => 'reqs'], function() {
	Route::post('/mark/{lesson}/{group}/{student}','teachers@setMark');
	Route::post('/date/{lesson}/{group}','teachers@setJDate');
	Route::post('/jmark/{student}/{date}','teachers@setJMark');
});

Route::group(['prefix' => 'teacher', 'middleware' => 'auth'], function() {
	Route::get('/', 'teachers@getLessons');
	Route::get('/lesson/{lesson}', 'teachers@getGroups');
	Route::get('/lesson/{lesson}/group/{group}','teachers@getList');
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
	Route::get('/', 'admins@main');
	Route::post('/add/{cat}', 'admins@addToCategory')->where('cat','teacher|lesson|group');
	Route::get('/lesson/{lesson}', 'admins@lesson');
	Route::get('/group/{group}', 'admins@group');
	Route::get('/teacher/{teacher}', 'admins@teacher');
	Route::get('/toggle/{student}', 'admins@toggle_student');
	Route::post('/group/{group}/add_student', 'adminss@add_student');
	Route::post('/teacher/{teacher}/add', 'admins@add_tgl');
});

Route::group(['prefix' => 'student'], function() {
	Route::get('/', 'students@getGroups');
	Route::get('/group/{group}', 'students@getLessons');
	Route::get('/group/{group}/lesson/{lesson}','students@getList');
});