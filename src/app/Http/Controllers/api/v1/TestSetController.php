<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Project;
use App\Requirement;
use App\TestSet;
use App\TestCase;

class TestSetController extends Controller
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
    public function index($projectId)
    {
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }
        $testSets = TestSet::where('SUT_id', $project->SUT_id)
                                    ->whereNull('ActiveDateTo')
                                    ->orderBy('ActiveDateFrom', 'asc')
                                    ->select('TestSet_id', 'SUT_id', 'Name','Author', 'TestSetDescription')
                                    ->get();
        foreach ($testSets as $testSet) {
            $testSet['TestCase'] = $testSet->testCases()->select('TestCase.TestCase_id', 'TestCase.Name')->get();
            $testSet['TestRun'] = $testSet->testRuns()->select('TestRun.TestRun_id', 'TestRun.TestSet_id', 'TestRun.Status')->get();
        }
        return response()->json($testSets);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $projectId)
    {
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }

        // Validation
        if ($request->input('Name') == null || strlen($request->input('Name') > 45)) {
            return response()->json(['error' => "Set name error"], 400);
        }
        if ($request->input('TestCases') == null) {
            return response()->json(['error' => "Testset have to contain testcases"], 400);
        }

        $testSet = new TestSet;
        $testSet->SUT_id = $projectId;
        $testSet->Name = $request->input('Name');
        $testSet->TestSetDescription = $request->input('TestSetDescription');
        $testSet->save();

        // test case attach
        foreach ($request->input('TestCases') as $testCase) {
            $testCaseObj = TestCase::find($testCase);
            if ($testCaseObj == null) {
                $testSet->delete();
                return response()->json(['error' => "TestCase with id $testCase don't exist"], 400);
            }
            if ($testCaseObj->ActiveDateTo != null) {
                $testSet->delete();
                return response()->json(['error' => "TestCase with id $testCase is not active"], 400);
            }
            $testSet->TestCases()->attach($testCase);
        }
        $testSet['Testcases'] = $testSet->TestCases()->select('TestCase.TestCase_id', 'TestCase.Name')->get();
        return response()->json($testSet, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($projectId, $testSetId)
    {
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }
        $testSet = TestSet::where('TestSet_id', $testSetId)
                                    ->select('TestSet_id', 'SUT_id', 'Name','Author', 'TestSetDescription')
                                    ->first();
        if ($testSet == null) {
            return response()->json(['error' => "Not existing set"], 404);
        }
        $testSet['TestCase'] = $testSet->testCases()->select('TestCase.TestCase_id', 'TestCase.Name')->get();
        $testSet['TestRun'] = $testSet->testRuns()->select('TestRun.TestRun_id', 'TestRun.TestSet_id', 'TestRun.Status')->get();

        return response()->json($testSet);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $projectId, $testSetId)
    {

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
}
