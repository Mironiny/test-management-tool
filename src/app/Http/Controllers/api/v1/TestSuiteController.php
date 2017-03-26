<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\TestSuite;
use App\TestCase;
use App\Http\Controllers\api\v1\Commons;

class TestSuiteController extends Controller
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
        $data = $this->filterTestSuite(TestSuite::whereNull('ActiveDateTo')->get());

        return response()->json($data);
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
    public function store(Request $request)
    {
        // Validation
        if ($request->input('Name') == null || strlen($request->input('Name') > 45)) {
            return response()->json(['error' => "Test case name error"], 400);
        }

        $testSuite = new TestSuite;
        $testSuite->Name = $request->input('Name');
        $testSuite->TestSuiteGoals = $request->input('TestSuiteGoals');
        $testSuite->TestSuiteVersion = $request->input('TestSuiteVersion');
        $testSuite->TestSuiteDocumentation = $request->input('TestSuiteDocumentation');

        $testSuite->save();
        return response()->json($this->filterTestSuite($testSuite), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->filterTestSuite(TestSuite::whereNull('ActiveDateTo')->where('TestSuite_id', $id)->get());

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
        if ($request->input('Name') == null || strlen($request->input('Name') > 45)) {
            return response()->json(['error' => "Test case name error"], 400);
        }
        $testSuite = TestSuite::where('TestSuite_id', $id)->first();
        if ($testSuite == null) {
            return response()->json(['error' => "Testsuite not found"], 404);
        }
        $testSuite->Name = $request->input('Name');
        $testSuite->TestSuiteGoals = $request->input('TestSuiteGoals');
        $testSuite->TestSuiteVersion = $request->input('TestSuiteVersion');
        $testSuite->TestSuiteDocumentation = $request->input('TestSuiteDocumentation');

        $testSuite->save();
        return response()->json($testSuite, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $testSuite = TestSuite::find($id);
        if ($testSuite == null) {
            return response()->json(['error' => "Testsuite not found"], 404);
        }
        $testSuite->ActiveDateTo = date("Y-m-d H:i:s");
        $testSuite->save();
        return response()->json(['success' => "Deleted"], 200);
    }

    /**
     * Show test cases by test suite.
     *
     * @param  int  $id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showTestCases($testSuiteId, $testCaseId = null)
    {
        $testSuite = TestSuite::find($testSuiteId);
        if ($testSuite == null) {
            return response()->json(['error' => 'Testsuit not found.'], 404);
        }
        $data = Commons::filterTestCase($testSuite->testCases()->whereNull('ActiveDateTo')->get());

        return response()->json($data);
    }

    /**
     * Filter information send to response.
     *
     * @param  int  $id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private function filterTestSuite($testSuites)
    {
        $data = [];

        foreach ($testSuites as $testSuite) {
            $entry = [
                'TestSuite_id' => $testSuite->TestSuite_id,
                'Name' => $testSuite->Name,
                'TestSuiteGoals' => $testSuite->TestSuiteGoals,
                'TestSuiteVersion' => $testSuite->TestSuiteVersion,
                'TestSuiteDocumentation' => $testSuite->TestSuiteDocumentation,
                'href' => route('testsuites.show', ['id' => $testSuite->TestSuite_id])
            ];
            $data[] = $entry;
        }
        return $data;
    }
}
