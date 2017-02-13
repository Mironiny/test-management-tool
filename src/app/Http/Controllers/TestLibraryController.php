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
        $testCases = TestCase::whereNull('ActiveDateTo')->get();
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
    public function createTestCaseForm()
    {
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();

        return view('library/createTestCase')
                ->with('testSuites', $testSuites);

    }

    /**
     * Show (render) the all test case page.
     *
     * @return view
     */
    public function renderTestCaseDetail(Request $request, $id)
    {
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();
        $testCase = TestCase::find( $id);
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
        $testCase->TestCaseSuffixes = $request->suffixes;

        $testCase->save();

        return redirect('library')->with('statusSuccess', trans('library.successEditedTestCase'));
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



}
