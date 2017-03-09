<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestCase;
use App\TestSuite;
use Illuminate\Support\Facades\Auth;

class TestLibraryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('projects');
    }

    /**
     * Show (render) the all test case page.
     *
     * @return view
     */
    public function index(Request $request)
    {
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();
        $testCases = collect();
        foreach ($testSuites as $testSuite) {
            $colection = $testSuite->testCases()->whereNull('ActiveDateTo')->get();
            $testCases = $testCases->merge($colection);
        }

        return view('library/libraryIndex')
                ->with('testSuites', $testSuites)
                ->with('testCases', $testCases)
                ->with('sidemenuToogle', true);
    }

    /**
     * Show (render) the all test case page.
     *
     * @return view
     */
    public function filterByTestSuite(Request $request, $id)
    {
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();
        $testCases = TestCase::where('TestSuite_id', $id)
                                ->whereNull('ActiveDateTo')
                                ->get();

        $selectedSuite = TestSuite::find($id);

        return view('library/libraryIndex')
                ->with('testSuites', $testSuites)
                ->with('testCases', $testCases)
                ->with('selectedSuite', $selectedSuite)
                ->with('sidemenuToogle', true);
    }

    /**
     * Show (render) test case create form.
     *
     * @return view
     */
    public function createTestCaseForm($selectedSuite = null)
    {
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();

        if ($selectedSuite == null) {
            return view('library/createTestCase')
                    ->with('testSuites', $testSuites);
        }
        else {
            return view('library/createTestCase')
                    ->with('testSuites', $testSuites)
                    ->with('selectedSuite', $selectedSuite);
        }

    }

    /**
     * Show (render) the all test case page.
     *
     * @return view
     */
    public function renderTestCaseDetail(Request $request, $id)
    {
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();
        $testCase = TestCase::find($id);
        // if (sizeof($testCases) < $id) {
        //     return redirect('library')->with('statusFailure', trans('library.testCaseNotExists'));
        // }
        // $testCase = $testCases[$id - 1];

        return view('library/testCaseDetail')
                ->with('testSuites', $testSuites)
                ->with('testCase', $testCase);

    }

    /**
     * Store test case to DB.
     *
     * @return view
     */
    public function storeTestCase(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:45',
            'testSuite' => 'required',
            'description' => 'max:1023',
            'prefixes' => 'max:1023',
            'suffixes' => 'max:1023'
        ]);

        $testCase = new TestCase;
        $testCase->Name = $request->name;
        $testCase->TestSuite_id = $request->testSuite;
        $testCase->TestCaseDescription = $request->description;
        $testCase->TestCasePrefixes = $request->prefixes;
        $testCase->TestSteps = $request->steps;
        $testCase->ExpectedResult = $request->expectedResult;
        $testCase->TestCaseSuffixes = $request->suffixes;
        $testCase->Author = Auth::user()->name;

        $testCase->save();

        return redirect('library')->with('statusSuccess', trans('library.successCreateTestCase'));
    }

    /**
     * Store test case to DB.
     *
     * @return view
     */
    public function updateTestCase(Request $request, $id)
    {
        $this->validate($request, [
            'testSuite' => 'required',
            'description' => 'max:1023'
        ]);

        $testCase = TestCase::find($id);
        // $testCase->Name = $request->name;
        $testCase->TestSuite_id = $request->testSuite;
        $testCase->TestCaseDescription = $request->description;
        $testCase->TestCasePrefixes = $request->prefixes;
        $testCase->TestSteps = $request->steps;
        $testCase->ExpectedResult = $request->expectedResult;
        $testCase->TestCaseSuffixes = $request->suffixes;

        $testCase->save();

        return redirect("library/testcase/detail/$id")->with('statusSuccess', trans('library.successEditedTestCase'));
    }

    /**
     * Delete test case to DB.
     *
     * @return view
     */
    public function deleteTestCase(Request $request, $id)
    {
        $testCase = TestCase::find($id);
        $testCase->ActiveDateTo = date("Y-m-d H:i:s");
        $testCase->save();

        return redirect('library')->with('statusSuccess', trans('library.deleteTestCase'));
    }

    /**
     * Show (render) test case create form.
     *
     * @return view
     */
    public function createTestSuiteForm()
    {
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();

        return view('library/createTestSuite')
                ->with('testSuites', $testSuites);

    }

    /**
     * Store test case to DB.
     *
     * @return view
     */
    public function storeTestSuite(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:45',
            'description' => 'max:1023',
            'goals' => 'max:1023',
        ]);

        $testSuite = new TestSuite;
        $testSuite->Name = $request->name;
        $testSuite->TestSuiteDocumentation = $request->decription;
        $testSuite->TestSuiteGoals = $request->goals;

        $testSuite->save();

        return redirect('library')->with('statusSuccess', trans('library.successCreateTestSuite'));
    }

    /**
     * Store test case to DB.
     *
     * @return view
     */
    public function updateTestSuite(Request $request, $id)
    {
        $testSuite = TestSuite::find($id);
        $testSuite->TestSuiteDocumentation = $request->description;
        $testSuite->TestSuiteGoals = $request->goals;

        $testSuite->save();

        return redirect("library/filter/$id")->with('statusSuccess', trans('library.successEditedTestSuite'));
    }

    /**
     * Delete test case to DB.
     *
     * @return view
     */
    public function deleteTestSuite(Request $request, $id)
    {
        $testSuite= TestSuite::find($id);
        $testSuite->ActiveDateTo = date("Y-m-d H:i:s");
        $testSuite->save();

        return redirect('library')->with('statusSuccess', trans('library.deleteTestSuite'));
    }

}
