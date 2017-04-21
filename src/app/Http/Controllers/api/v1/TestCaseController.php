<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TestCase;
use App\TestCaseOverview;
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
        $data = Commons::filterTestCase(TestCase::whereNull('ActiveDateTo')->get());

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

        $testCaseOverview = new TestCaseOverview;
        $testCaseOverview->TestSuite_id = $request->input('TestSuite_id');
        $testCaseOverview->save();

        $testCase = new TestCase;
        $testCase->TestCaseOverview_id = $testCaseOverview->TestCaseOverview_id;
        $testCase->Name =  $request->input('Name');
        $testCase->IsManual =  $request->input('IsManual');
        $testCase->TestCasePrefixes =  $request->input('TestCasePrefixes');
        $testCase->TestSteps =  $request->input('TestSteps');
        $testCase->ExpectedResult =  $request->input('ExpectedResult');
        $testCase->TestCaseSuffixes =  $request->input('TestCaseSuffixes');
        $testCase->SourceCode =  $request->input('SourceCode');
        $testCase->TestCaseDescription =  $request->input('TestCaseDescription');
        $testCase->Note =  $request->input('Note');

        $testCase->save();

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
        $data = Commons::filterTestCase(TestCase::whereNull('ActiveDateTo')->where('TestCase_id', $id)->get());

        return response()->json($data);
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
        // Validation
        // if ($request->input('TestSuite_id') == null) {
        //     return response()->json(['error' => "Test suite doesn't pass in request"], 400);
        // }
        // $testSuite = TestSuite::find($request->input('TestSuite_id'));
        // if ($testSuite == null) {
        //     return response()->json(['error' => "Test suite doesn't exists"], 400);
        // }
        // if ($request->input('Name') == null || strlen($request->input('Name') > 45)) {
        //     return response()->json(['error' => "Test case name error"], 400);
        // }
        $testCase = TestCase::find($id);
        if ($testCase == null) {
            return response()->json(['error' => "TestCase not found"], 404);
        }
        if ($request->input('TestSuite_id') != null) {
            $testCase->TestSuite_id = $request->input('TestSuite_id');
        }
        if ($request->input('Name')) {
            $testCase->Name =  $request->input('Name');
        }

        $testCase->IsManual =  $request->input('IsManual');
        $testCase->TestCasePrefixes =  $request->input('TestCasePrefixes');
        $testCase->TestSteps =  $request->input('TestSteps');
        $testCase->ExpectedResult =  $request->input('ExpectedResult');
        $testCase->TestCaseSuffixes =  $request->input('TestCaseSuffixes');
        $testCase->SourceCode =  $request->input('SourceCode');
        $testCase->TestCaseDescription =  $request->input('TestCaseDescription');
        $testCase->Note =  $request->input('Note');

        $testCase->save();

        return response()->json($testCase, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $testcase = TestCase::find($id);
        if ($testcase == null) {
            return response()->json(['error' => "Testcase not found"], 404);
        }
        $testcase->ActiveDateTo = date("Y-m-d H:i:s");
        $testcase->save();
        return response()->json(['success' => "Deleted"], 200);
    }

}
