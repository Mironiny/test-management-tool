<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TestCaseHistory;
use App\TestCase;
use App\TestSuite;

class TestCaseController extends Controller
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
    public function index()
    {
        $testCases = TestCaseHistory::whereNull('ActiveDateTo')->get();
        foreach ($testCases as $testCase) {
            $testCaseOverview = $testCase->testCaseOverview->get();
            $testCase['TestCase_id'] = TestCase::find($testCase->TestCaseOverview_id)->TestCaseOverview_id;
            $testCase['Name'] = TestCase::find($testCase->TestCaseOverview_id)->Name;
            $testCase['TestSuite_id'] = $testCase->testCaseOverview->TestSuite_id;
        }
        $data = Commons::filterTestCase($testCases);

        return response()->json($data);
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
    public function store(Request $request)
    {
        // Validation
        if ($request->input('TestSuite_id') == null) {
            return response()->json(['error' => "Test suite doesn't pass in request"], 400);
        }
        $testSuite = TestSuite::find($request->input('TestSuite_id'));
        if ($testSuite == null) {
            return response()->json(['error' => "Test suite doesn't exists"], 400);
        }
        if ($request->input('Name') == null || strlen($request->input('Name') > 45)) {
            return response()->json(['error' => "Test case name error"], 400);
        }

        $testCaseOverview = new TestCase;
        $testCaseOverview->TestSuite_id = $request->input('TestSuite_id');
        $testCaseOverview->Name =  $request->input('Name');
        $testCaseOverview->save();

        $testCase = new TestCaseHistory;
        $testCase->TestCaseOverview_id = $testCaseOverview->TestCaseOverview_id;
        $testCase->Version_id =  1;
        $testCase->IsManual =  $request->input('IsManual');
        $testCase->TestCasePrefixes =  $request->input('TestCasePrefixes');
        $testCase->TestSteps =  $request->input('TestSteps');
        $testCase->ExpectedResult =  $request->input('ExpectedResult');
        $testCase->TestCaseSuffixes =  $request->input('TestCaseSuffixes');
        $testCase->SourceCode =  $request->input('SourceCode');
        $testCase->TestCaseDescription =  $request->input('TestCaseDescription');
        $testCase->Note =  $request->input('Note');

        $testCase->save();
        $testCase['TestCase_id'] = $testCase->TestCaseOverview_id;
        $testCase['TestSuite_id'] = $testCaseOverview->TestSuite_id;
        $testCase['Name'] = $testCaseOverview->Name;
        unset($testCase['TestCaseOverview_id']);
        unset($testCase['Version_id']);


        return response()->json($testCase, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $testCaseOverview = TestCase::find($id);
        if ($testCaseOverview != null) {
            $testCase = $testCaseOverview->testCases()->whereNull('ActiveDateTo')->first();
            $testCase['Name'] = $testCaseOverview->Name;
            unset($testCase['TestCaseOverview_id']);
            $testCase['TestCase_id'] = $id;
            $testCase['TestSuite_id'] = (int) $testCaseOverview->TestSuite_id;
            //$data = Commons::filterTestCase($testCase);

            return response()->json($testCase);
        }

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
    public function update(Request $request, $id)
    {
        $testCaseOverview = TestCase::find($id);
        if ($testCaseOverview == null) {
            return response()->json(['error' => "TestCase not found"], 404);
        }
        $testCase = $testCaseOverview->testCases()->whereNull('ActiveDateTo')->first();
        if ($testCase == null) {
            return response()->json(['error' => "TestCase not found"], 404);
        }
        $testCase->ActiveDateTo = date("Y-m-d H:i:s");
        $testCase->save();

        $newTestCase = new TestCaseHistory;
        $newTestCase->TestCaseOverview_id = $id;
        $newTestCase->IsManual =  $request->input('IsManual');
        $newTestCase->TestCasePrefixes =  $request->input('TestCasePrefixes');
        $newTestCase->TestSteps =  $request->input('TestSteps');
        $newTestCase->ExpectedResult =  $request->input('ExpectedResult');
        $newTestCase->TestCaseSuffixes =  $request->input('TestCaseSuffixes');
        $newTestCase->SourceCode =  $request->input('SourceCode');
        $newTestCase->TestCaseDescription =  $request->input('TestCaseDescription');
        $newTestCase->Note =  $request->input('Note');
        $newTestCase->Version_id = $testCaseOverview->testCases()->count() + 1;

        $newTestCase->save();
        $newTestCase['Name'] = $testCaseOverview->Name;
        $newTestCase['SUT_id'] = $testCaseOverview->SUT_id;
        $newTestCase['TestSuite_id'] = $testCaseOverview->TestSuite_id;

        return response()->json($newTestCase, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $testcase = TestCaseHistory::find($id);
        if ($testcase == null) {
            return response()->json(['error' => "Testcase not found"], 404);
        }
        $testcase->ActiveDateTo = date("Y-m-d H:i:s");
        $testcase->save();
        return response()->json(['success' => "Deleted"], 200);
    }

}
