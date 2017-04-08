<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Project;
use App\Requirement;
use App\TestSet;
use App\TestCase;
use App\TestRun;
use App\Enums\TestRunStatus;
use App\Enums\TestCaseStatus;

class TestRunController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($projectId, $testSetId)
    {
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }
        $testSet = TestSet::find($testSetId);
        if ($testSet == null) {
            return response()->json(['error' => "Testset don't exist"], 404);
        }
        // if ($testSet->SUT_id != $projectId) {
        //     return response()->json(['error' => "Testset don't existsflajfsj"], 400);
        // }
        $testRuns = TestRun::where('TestSet_id', $testSetId)
                                ->whereNull('ActiveDateTo')
                                ->orderBy('ActiveDateFrom', 'asc')
                                ->select('TestRun_id', 'TestSet_id', 'Status')
                                ->get();
        foreach ($testRuns as $testRun) {
            $testRun['TestCase'] = $testRun->testCases()->select('TestCase.TestCase_id', 'TestCase.Name')->get();
            $testCases =  $testRun->testCases()->select('TestCase.TestCase_id', 'TestCase.Name')->get();
            foreach ($testCases as $testCase) {
                $testCase['Status'] = $testRun->testCases()->where('TestCase.TestCase_id', $testCase->TestCase_id)->first()->pivot->Status;
            }
            $testRun['TestCase'] = $testCases;
        }

        return response()->json($testRuns);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $projectId, $testSetId)
    {
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }

        $testSet = TestSet::find($testSetId);
        if ($testSet == null) {
            return response()->json(['error' => "Testset don't exist"], 400);
        }
        if ($testSet->SUT_id != $projectId) {
            return response()->json(['error' => "Testset not belongs to projectId"], 400);
        }
        $testRun = new TestRun;
        $testRun->TestSet_id = $testSetId;
        $testRun->Status = TestRunStatus::RUNNING;

        $testRun->save();


        // Copy test set testcases to run
        foreach ($testSet->testCases as $testCase) {
            $testRun->testCases()->attach($testCase->TestCase_id);
            $testRun->testCases()->updateExistingPivot($testCase->TestCase_id, ['Status' => TestCaseStatus::NOT_TESTED]);
        }

        $testCases =  $testRun->testCases()->select('TestCase.TestCase_id', 'TestCase.Name')->get();
        foreach ($testCases as $testCase) {
            $testCase['Status'] = $testRun->testCases()->where('TestCase.TestCase_id', $testCase->TestCase_id)->first()->pivot->Status;
        }
        $testRun['TestCase'] = $testCases;

        return response()->json($testRun, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($projectId, $testSetId, $testRunId)
    {
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }
        $testSet = TestSet::find($testSetId);
        if ($testSet == null) {
            return response()->json(['error' => "Testset don't exist"], 400);
        }
        if ($testSet->SUT_id != $projectId) {
            return response()->json(['error' => "Testset not belongs to projectId"], 400);
        }
        $testRun = TestRun::where('TestRun_id', $testRunId)
                             ->select('TestRun_id', 'TestSet_id', 'Status')
                             ->first();
        return response()->json($testRun);

        $testRun['TestCase'] = $testRun->testCases()->select('TestCase.TestCase_id', 'TestCase.Name')->get();
        $testCases =  $testRun->testCases()->select('TestCase.TestCase_id', 'TestCase.Name')->get();
        foreach ($testCases as $testCase) {
            $testCase['Status'] = $testRun->testCases()->where('TestCase.TestCase_id', $testCase->TestCase_id)->first()->pivot->Status;
        }
        $testRun['TestCase'] = $testCases;

        return response()->json($testRun);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $projectId, $testSetId, $testRunId)
    {
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }

        $testSet = TestSet::find($testSetId);
        if ($testSet == null) {
            return response()->json(['error' => "Testset don't exist"], 400);
        }
        if ($testSet->SUT_id != $projectId) {
            return response()->json(['error' => "Testset not belongs to projectId"], 400);
        }

        $testRun = TestRun::find($testRunId);
        if ($testRun == null) {
            return response()->json(['error' => "TestRun Not exist"], 400);
        }

        if ($request->input('Status') == TestRunStatus::RUNNING || $request->input('Status') == TestRunStatus::FINISHED || $request->input('Status') == TestRunStatus::ARCHIVED) {
            $testRun->Status = $request->input('Status');
            $testRun->save();
            return response()->json($testRun);
        }

        $testCases =  $testRun->testCases()->select('TestCase.TestCase_id', 'TestCase.Name')->get();
        foreach ($testCases as $testCase) {
            $testCase['Status'] = $testRun->testCases()->where('TestCase.TestCase_id', $testCase->TestCase_id)->first()->pivot->Status;
        }
        $testRun['TestCase'] = $testCases;

        return response()->json(['error' => "Not supported status"], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Change status of test run
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeTestCaseStatus(Request $request, $projectId, $testSetId, $testRunId, $testCaseId)
    {
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }

        $testSet = TestSet::find($testSetId);
        if ($testSet == null) {
            return response()->json(['error' => "Testset don't exist"], 400);
        }
        if ($testSet->SUT_id != $projectId) {
            return response()->json(['error' => "Testset not belongs to projectId"], 400);
        }

        $testRun = TestRun::find($testRunId);
        if ($testRun == null) {
            return response()->json(['error' => "TestRun Not exist"], 400);
        }

        $testCase = TestCase::find($testCaseId);
        if ($testCase == null) {
            return response()->json(['error' => "TestCase Not exist"], 400);
        }
        if (!$testRun->testCases()->get()->contains('TestCase_id', $testCaseId)) {
            return response()->json(['error' => "TestRun not contains testcase"], 400);
        }
        if ($request->input('Status') == TestCaseStatus::NOT_TESTED || $request->input('Status') == TestCaseStatus::PASS || $request->input('Status') == TestCaseStatus::FAIL
                    ||  $request->input('Status') == TestCaseStatus::BLOCKED  ) {

            $testRun->testCases()->updateExistingPivot($testCaseId, ['Status' => $request->input('Status')]);
            $testRun->testCases()->updateExistingPivot($testCaseId, ['Author' => Auth::user()->name]);
            $testRun->testCases()->updateExistingPivot($testCaseId, ['LastUpdate' => date("Y-m-d H:i:s")]);

            $testCases =  $testRun->testCases()->select('TestCase.TestCase_id', 'TestCase.Name')->get();
            foreach ($testCases as $testCase) {
                $testCase['Status'] = $testRun->testCases()->where('TestCase.TestCase_id', $testCase->TestCase_id)->first()->pivot->Status;
            }
            $testRun['TestCase'] = $testCases;

            return response()->json($testRun);
        }

        return response()->json(['error' => "Not supported status"], 400);

    }
}
