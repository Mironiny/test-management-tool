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

use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;

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
Route::get('requirements/detail/{id}', 'RequirementsController@renderRequirementDetail');
Route::post('requirements/update/{id}', 'RequirementsController@updateRequirement');
Route::get('requirements/terminate/{id}', 'RequirementsController@deleteRequirement');

/**
 * Projects routes.
 */
Route::get('projects', 'ProjectsController@index');
Route::get('projects/create', 'ProjectsController@createProjectForm');
Route::post('projects/create', 'ProjectsController@storeProject');
Route::post('projects/changeproject', 'ProjectsController@changeProject');
Route::get('projects/detail/{id}', 'ProjectsController@renderProjectDetail');
Route::post('projects/update/{id}', 'ProjectsController@updateProject');
Route::get('projects/terminate/{id}', 'ProjectsController@terminateProject');

/**
 * Testing reasons.
 */
 Route::get('sendmail', function(Request $request, Mailer $mailer) {
     $mailer->to('mirek@seznam.cz')->send(new \App\Mail\MyMail('Title'));
 });
