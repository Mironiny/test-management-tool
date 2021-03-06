<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestCaseHistory;
use App\TestCase;
use App\TestSuite;
use Illuminate\Support\Facades\Auth;
use App\Services\TestLibraryService;

class TestLibraryController extends Controller
{
    protected $libraryService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TestLibraryService $testSuiteService)
    {
        $this->middleware('auth');
        $this->middleware('projects');
        $this->libraryService = $testSuiteService;
    }

    /**
     * Show (render) the all test case page.
     *
     * @return view
     */
    public function index(Request $request)
    {
        $testSuites = $this->libraryService->getActiveSuites();
        $testCases = $this->libraryService->getActiveTestCases();

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
        $testSuites = $this->libraryService->getActiveSuites();

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
        $testSuites = $this->libraryService->getActiveSuites();
        $testCase = $this->libraryService->getBaseTestCaseById($id);
        $testCaseHistory = $this->libraryService->getCurrentTestCaseById($id);

        return view('library/testCaseDetail')
                ->with('testSuites', $testSuites)
                ->with('testCaseHistory', $testCaseHistory)
                ->with('testCase', $testCase);

    }

    /**
     * Store test case to DB.
     *
     * @return view
     */
    public function storeTestCase(Request $request)
    {
        $testCaseCount =  $request->testCaseCount;
        for ($i = 1; $i <= $testCaseCount; $i++) {
            $this->validate($request, [
                "name$i" => 'required|max:45',
                "testSuite$i" => 'required',
                "description$i" => 'max:1023',
                "prefixes$i" => 'max:1023',
                "suffixes$i" => 'max:1023'
            ]);
        }

        for ($i = 1; $i <= $testCaseCount; $i++) {
            $testCaseOverview = new TestCase;
            $testCaseOverview->TestSuite_id = $request["testSuite$i"];
            $testCaseOverview->Name = $request["name$i"];
            $testCaseOverview->save();

            $testCase = new TestCaseHistory;
            $testCase->TestCaseOverview_id = $testCaseOverview->TestCaseOverview_id;
            $testCase->Version_id = 1;
            $testCase->TestCaseDescription = $request["description$i"];
            $testCase->TestCasePrefixes = $request["prefixes$i"];
            $testCase->TestSteps = $request["steps$i"];
            $testCase->ExpectedResult = $request["expectedResult$i"];
            $testCase->TestCaseSuffixes = $request["suffixes$i"];
            $testCase->Author = Auth::user()->name;
            $testCase->save();
        }

        if (isset($request->selectedSuite)) {
            return redirect("library/filter/$request->selectedSuite")->with('statusSuccess', "Successfully added $testCaseCount Test Case(s)");
        }
        else {
            return redirect("library")->with('statusSuccess', "Successfully added $testCaseCount Test Case(s)");
        }

    }

    /**
     * Store test case to DB.
     *
     * @return view
     */
    public function updateTestCase(Request $request, $id)
    {
        $this->validate($request, [
            'description' => 'max:1023'
        ]);

        $testCaseOverview = TestCase::find($id);
        $testCaseDetail = $testCaseOverview
            ->testCases()
            ->whereNull('ActiveDateTo')
            ->first();

        // Check if there is a change
        if ($testCaseDetail->TestCaseDescription == $request->description &&
            $testCaseDetail->TestCasePrefixes == $request->prefixes &&
            $testCaseDetail->TestSteps == $request->steps &&
            $testCaseDetail->ExpectedResult ==  $request->expectedResult &&
            $testCaseDetail->TestCaseSuffixes == $request->suffixes) {
            return redirect("library/testcase/detail/$id")->with('statusSuccess', "Nothing to change");
        }

        // Record will be not active
        $testCaseDetail->ActiveDateTo = date("Y-m-d H:i:s");
        $testCaseDetail->save();

        $testCaseNew = new TestCaseHistory;
        $testCaseNew->Version_id = $testCaseOverview->testCases()->count() + 1;
        $testCaseNew->TestCaseOverview_id = $id;
        $testCaseNew->TestCaseDescription = $request->description;
        $testCaseNew->TestCasePrefixes = $request->prefixes;
        $testCaseNew->TestSteps = $request->steps;
        $testCaseNew->ExpectedResult = $request->expectedResult;
        $testCaseNew->TestCaseSuffixes = $request->suffixes;

        $testCaseNew->save();

        return redirect("library/testcase/detail/$id")->with('statusSuccess', trans('library.successEditedTestCase'));

    }

    /**
     * Delete test case to DB.
     *
     * @return view
     */
    public function deleteTestCase(Request $request, $id)
    {
        $testCaseOverview = TestCase::find($id);
        $testCaseOverview->ActiveDateTo = date("Y-m-d H:i:s");
        $testCaseOverview->save();

        // $testCase = TestCase::find($id);
        // $testCase->ActiveDateTo = date("Y-m-d H:i:s");
        // $testCase->save();

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
        $testSuite->TestSuiteDocumentation = $request->description;
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
        $testSuite->Name = $request->name;
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

    /**
     * Change actual selected version of requirement.
     *
     * @return view
     */
    public function changeVersion(Request $request, $id)
    {
        $testCaseOverview = TestCase::find($id);
        $testCaseOld = $testCaseOverview->testCases()
                                            ->whereNull('ActiveDateTo')
                                            ->first();

        $testCaseOld->ActiveDateTo = date("Y-m-d H:i:s");
        $testCaseOld->save();

        $testCaseNew = TestCaseHistory::find($request->versionToChange);
        $testCaseNew->ActiveDateTo = null;
        $testCaseNew->save();

        return redirect("library/testcase/detail/$id")->with('statusSuccess', "Version of requirement changed");

    }

    /**
     * Remove version from db.
     *
     * @return view
     */
    public function removeVersion(Request $request, $id)
    {
        $requirement = Requirement::find($request->versionToChangeRemove);
        $requirement->delete();

        return redirect("requirements/detail/$id")->with('statusSuccess', "Version was deleted");

    }

}
