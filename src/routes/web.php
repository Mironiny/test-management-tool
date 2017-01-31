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

Route::get('/', function () {
    return redirect('dashboard');
});
Route::get('dashboard', 'DashboardController@index');

Route::get('requirements', 'RequirementsController@index');

/**
 * Basic Auth routes.
 */
Auth::routes();

Route::get('/home', 'HomeController@indexDefault');
