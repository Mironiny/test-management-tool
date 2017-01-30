<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::get('/', 'HomeController@index');

// Route::get('/', function () {
//     //return view('welcome');
//     return "Hello world";
// }) -> middleware('auth');

/**
 * Basic Auth routes.
 */
Auth::routes();

Route::get('/home', 'HomeController@indexDefault');
