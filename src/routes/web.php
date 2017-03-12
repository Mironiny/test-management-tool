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
 * User and settings routes.
 */
Route::get('user', 'UserController@index');

/**
 * Requirements routes.
 */
Route::get('requirements', 'RequirementsController@index');
Route::get('requirements/create', 'RequirementsController@createRequirementForm');
Route::post('requirements/create', 'RequirementsController@storeRequirement');
Route::get('requirements/detail/{id}', 'RequirementsController@renderRequirementDetail');
Route::post('requirements/update/{id}', 'RequirementsController@updateRequirement');
Route::get('requirements/terminate/{id}', 'RequirementsController@deleteRequirement');
Route::post('requirements/cover/{id}', 'RequirementsController@coverRequirement');

/**
 * Test sets and runs routes.
 */
Route::get('sets_runs', 'TestRunController@index');
Route::get('sets_runs/filter/finished', 'TestRunController@filterNotActive');
Route::get('sets_runs/set/create', 'TestRunController@createSetForm');
Route::post('sets_runs/set/create', 'TestRunController@storeSet');
Route::post('sets_runs/set/finish/{id}', 'TestRunController@terminateSet');
Route::post('sets_runs/set/update/{id}', 'TestRunController@updateSet');
Route::post('sets_runs/set/updateTestCases/{id}', 'TestRunController@updateSetTestCases');
Route::get('sets_runs/set/detail/{id}', 'TestRunController@renderSetDetail');
Route::post('sets_runs/run/create', 'TestRunController@createRun');
Route::get('sets_runs/run/execution/{runId}/testcase/{testcaseId?}', 'TestRunController@renderTestRunDetail');
Route::post('sets_runs/run/execution/{runId}/testcase/{testcaseId}', 'TestRunController@updateTestRunTestCase');
Route::post('sets_runs/run/execution/{runId}/close', 'TestRunController@closeTestRun');

 /**
  * Test library routes.
  */
 Route::get('library', 'TestLibraryController@index');
 Route::get('library/filter/{id}', 'TestLibraryController@filterByTestSuite');
 Route::get('library/testcase/create/{selectedSuite?}', 'TestLibraryController@createTestCaseForm');
 Route::get('library/testcase/detail/{id}', 'TestLibraryController@renderTestCaseDetail');
 Route::post('library/testcase/create', 'TestLibraryController@storeTestCase');
 Route::post('library/testcase/update/{id}', 'TestLibraryController@updateTestCase');
 Route::get('library/testcase/terminate/{id}', 'TestLibraryController@deleteTestCase');

 Route::get('library/testsuite/create', 'TestLibraryController@createTestSuiteForm');
 Route::post('library/testsuite/create', 'TestLibraryController@storeTestSuite');
 Route::post('library/testsuite/update/{id}', 'TestLibraryController@updateTestSuite');
 Route::get('library/testsuite/terminate/{id}', 'TestLibraryController@deleteTestSuite');

/**
 * Projects routes.
 */
Route::get('projects', 'ProjectsController@index');
Route::get('projects/filter/finished', 'ProjectsController@filterFinished');
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
