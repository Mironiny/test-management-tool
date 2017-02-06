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

/**
 * Basic Auth routes.
 */
Auth::routes();

/**
 * Main application route.
 */
Route::get('/', function () {
    return redirect('dashboard');
});

/**
 * Dashboard routes.
 */
Route::get('dashboard', 'DashboardController@index');

/**
 * Requirements routes.
 */
Route::get('requirements', 'RequirementsController@index');
Route::get('requirements/create', 'RequirementsController@createRequirementForm');
Route::post('requirements/create', 'RequirementsController@storeRequirement');

/**
 * Projects routes.
 */
Route::get('projects', 'ProjectsController@index');
Route::get('projects/create', 'ProjectsController@createProjectForm');
Route::post('projects/create', 'ProjectsController@storeProject');
Route::post('projects/changeproject', 'ProjectsController@changeProject');
