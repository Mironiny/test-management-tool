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

Route::resource('v1/projects', api\v1\ProjectController::class);

Route::resource('v1/testcases', api\v1\TestCaseController::class);

Route::get('v1/testsuites/{suiteID}/testcases/{caseId?}', 'api\v1\TestSuiteController@showTestCases');
Route::resource('v1/testsuites', api\v1\TestSuiteController::class);

Route::resource('v1/projects/{projectID}/requirements', api\v1\RequirementController::class);
