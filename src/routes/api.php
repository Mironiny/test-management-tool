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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Project api
Route::resource('v1/projects', api\v1\ProjectController::class);

// TestCase api
Route::resource('v1/testcases', api\v1\TestCaseController::class);

// TestSuite api
Route::get('v1/testsuites/{suiteID}/testcases/{caseId?}', 'api\v1\TestSuiteController@showTestCases');
Route::resource('v1/testsuites', api\v1\TestSuiteController::class);

// Test requirements api
Route::resource('v1/projects/{projectID}/requirements', api\v1\RequirementController::class);

// Test set api
Route::resource('v1/projects/{projectID}/testsets', api\v1\TestSetController::class);

// Test run api
Route::post('v1/projects/{projectID}/testsets/{testSetId}/testruns/{testRunId}/testcase/{testCaseID}', 'api\v1\TestRunController@changeTestCaseStatus');
Route::resource('v1/projects/{projectID}/testsets/{testSetId}/testruns', api\v1\TestRunController::class);
