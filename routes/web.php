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
Route::post('/login', 'Auth\LoginController@login');
Route::get('/login', 'Auth\LoginController@showLoginForm');

Auth::routes();

Route::get('/klubovi', 'KluboviController@index');
Route::get('/dashboard','DashboardController@index');
Route::get('/','DashboardController@index');
Route::get('/home','DashboardController@index');
Route::get('/users', 'KorisniciController@index');
Route::get('/objekti', function(){
	return view('objekti');
});
Route::get('/prijave', function(){
	return view('prijave');
});
Route::get('/news', function(){
	return view('news');
});
Route::get('/sportisti', 'SportistiController@index');
Route::get('/unauth/news', function(){
	return view('unews');
});

Route::get('/unauth/klubovi', function(){
	return view('uklubovi');
});
Route::get('/unauth/sportisti', function(){
	return view('usportisti');
});
Route::get('/unauth/objekti', function(){
	return view('uobjekti');
});
Route::get('/unauth/priajve', function(){
	return view('uprijave');
});
