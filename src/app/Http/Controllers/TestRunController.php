<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Project;
use App\TestSet;
use App\TestRun;
use App\TestSuite;
use App\TestCaseHistory;
use App\TestCase;
use App\Enums\TestRunStatus;
use App\Enums\TestCaseStatus;
use Charts;

class TestRunController extends Controller
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
     * Show (render) the test run page.
     *
     * @return view
     */
    public function index(Request $request)
    {
        $selectedProject = $request->session()->get('selectedProject');

        if (Project::all()->count() > 0) {
            $testSets = TestSet::where('SUT_id', $selectedProject)
                                        ->whereNull('ActiveDateTo')
                                        ->orderBy('ActiveDateFrom', 'asc')
                                        ->get();
            return view('runs/runsIndex')->with('testSets', $testSets);
        }
        else {
            return view('runs/runsIndex');
        }

    }

    /**
     * Filter by finished test set.
     *
     * @return view
     */
    public function filterNotActive(Request $request)
    {
        $selectedProject = $request->session()->get('selectedProject');

        if (Project::all()->count() > 0) {
            $testSets = TestSet::where('SUT_id', $selectedProject)
                                        ->whereNotNull('ActiveDateTo')
                                        ->orderBy('ActiveDateFrom', 'asc')
                                        ->get();
            return view('runs/runsIndex')->with('testSets', $testSets);
        }
        else {
            return view('runs/runsIndex');
        }

    }

    /**
     * Show (render) the test run page.
     *
     * @return view
     */
    public function createSetForm(Request $request)
    {
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();
        return view('runs/createTestSet')
            ->with('testSuites', $testSuites);
    }

    /**
     * Store test set.
     *
     * @return view
     */
    public function storeSet(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:45',
            'testcases' => 'required'
        ]);
        $set = new TestSet;
        $set->Name = $request->name;
        if ($request->session()->get('selectedProject') == 0) {
            return redirect('requirements')->with('statusFailure', trans('requirements.failureCreateRequirementNotSelectedProject'));
        }
        $set->SUT_id = $request->session()->get('selectedProject');
        $set->TestSetDescription = $request->description;
        $set->save();

        // Adding to database
        if ($request->testcases != null) {
            foreach ($request->testcases as $testCase ) {
                $testCaseOverview = TestCase::find($testCase);
                $testCase = $testCaseOverview
                            ->testCases()
                            ->whereNull('ActiveDateTo')
                            ->first();
                $set->TestCases()->attach($testCase->TestCase_id);
            }
        }
        return redirect('sets_runs')->with('statusSuccess',"Test set was created");
    }

    /**
     * Update test set.
     *
     * @return view
     */
    public function updateSet(Request $request, $id)
    {
        $this->validate($request, [
            // 'name' => 'required|max:45',
            'description' => 'max:1023'
        ]);

        $set = TestSet::find($id);
        $set->TestSetDescription = $request->description;
        $set->save();
        return redirect("sets_runs/set/detail/$id")
            ->with('statusSuccess', "Test set was edited")
            ->with('sidemenuToogle', true);
    }

    /**
     * Terminate test set.
     *
     * @return view
     */
    public function terminateSet(Request $request, $id)
    {
        $set = TestSet::find($id);
        $set->ActiveDateTo = date("Y-m-d H:i:s");
        $set->save();

        return redirect("sets_runs")->with('statusSuccess', "Test set finished");
    }
    /**
     * Terminate test set.
     *
     * @return view
     */
    public function renderSetTestCasesView(Request $request, $id)
    {
        $selectedProject = $request->session()->get('selectedProject');
        $set = TestSet::find($id);
        if ($set->SUT_id != $selectedProject) {
            return redirect('sets_runs')->with('statusFailure', "Test set doesn't exist");
        }
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();
        $testCases = $set->testCases()
                          ->join('TestCase', 'TestCase.TestCaseOverview_id', '=', 'TestCase.TestCaseOverview_id')
                          ->orderBy('TestCase.TestCaseOverview_id')
                          ->get();
        return view('runs/testSetTestCases')
                ->with('set', $set)
                ->with('testCases', $testCases)
                ->with('testSuites', $testSuites);
    }

    /**
     * Update test cases of test set.
     *
     * @return view
     */
    public function updateSetTestCases(Request $request, $id)
    {
        $this->validate($request, [
            'testcases' => 'required',
        ]);

        $coveredTestCases = TestSet::find($id)->TestCases()->get();
        $selectedTestcases = ($request->testcases != null) ? $request->testcases : array();

        // Adding to database
        if (!empty($selectedTestcases)) {
            foreach ($selectedTestcases as $selectedTestCase ) {
                if (!$coveredTestCases->contains('TestCase_id', $selectedTestCase)) {
                    TestSet::find($id)->TestCases()->attach($selectedTestCase);
                }
            }
        }
        // Deleting from db
        foreach ($coveredTestCases as $coveredTestCase) {
            if (!in_array($coveredTestCase->TestCase_id, $selectedTestcases)) {
                TestSet::find($id)->TestCases()->detach($coveredTestCase->TestCase_id);
            }
        }

        return redirect("sets_runs/set/detail/$id")->with('statusSuccess', "Test set was edited");
    }


    /**
     * Show (render) the test run page.
     *
     * @return view
     */
    public function renderSetDetail(Request $request, $id)
    {
        $selectedProject = $request->session()->get('selectedProject');
        $set = TestSet::find($id);
        if ($set->SUT_id != $selectedProject) {
            return redirect('sets_runs')->with('statusFailure', "Test set doesn't exist");
        }
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();

        // Sort (First every running by LastUpdate then finished by LastUpdate)
        $runs = $set->testRuns->where('Status', '!=', TestRunStatus::ARCHIVED)->sort(function($a, $b) {
           if($a->Status === $b->Status) {
             if($a->LastUpdate === $b->LastUpdate) {
               return 0;
             }
             return $a->LastUpdate > $b->LastUpdate ? -1 : 1;
           }
           return $a->Status > $b->Status ? -1 : 1;
       });

        return view('runs/testSetDetail')
            ->with('set', $set)
            ->with('testSuites', $testSuites)
            ->with('runs', $runs);
    }

    /**
     * Show (render) the test run page.
     *
     * @return view
     */
    public function renderSetDetailFiltered(Request $request, $id)
    {
        {
            $selectedProject = $request->session()->get('selectedProject');
            $set = TestSet::find($id);
            if ($set->SUT_id != $selectedProject) {
                return redirect('sets_runs')->with('statusFailure', "Test set doesn't exist");
            }
            $testSuites = TestSuite::whereNull('ActiveDateTo')->get();
            $runs = $set->testRuns->sortByDesc('ActiveDateFrom')->where('Status', TestRunStatus::ARCHIVED);

            return view('runs/testSetDetail')
                ->with('set', $set)
                ->with('testSuites', $testSuites)
                ->with('runs', $runs);
        }
    }

    /**
     *
     *
     * @return view
     */
    public function testRunChangeStatus(Request $request)
    {
        $run = TestRun::find($request->testRunId);
        $run->Status = $request->status;
        $run->LastUpdate = date("Y-m-d H:i:s");
        $run->save();
        return redirect("sets_runs/set/detail/$run->TestSet_id")->with('statusSuccess', "Status edited");;
    }

    /**
     * Create new test run and redirect to run detail.
     *
     * @return view
     */
    public function createRun(Request $request)
    {
        $set = TestSet::find($request->setId);
        $run = new TestRun;
        $run->Status = TestRunStatus::RUNNING;
        $run->TestSet()->associate($set);
        $run->save();

        // Copy test set testcases to run
        foreach ($set->testCases as $testCase) {
            $run->testCases()->attach($testCase->TestCase_id);
            $run->testCases()->updateExistingPivot($testCase->TestCase_id, ['Status' => TestCaseStatus::NOT_TESTED]);
        }

        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();
        $testCases = $run->testCases();
        $selectedTestCase = $testCases->first();

        $runs = $set->testRuns;
        return redirect("sets_runs/run/execution/$run->TestRun_id/overview");

    }

    /**
     * Render test run detail
     *
     * @return view
     */
    public function renderTestRunDetail(Request $request, $testRunId, $testCaseId = null)
    {
        $selectedProject = $request->session()->get('selectedProject');
        $run = TestRun::find($testRunId);
        if ($run->testSet->SUT_id != $selectedProject) {
            return redirect('sets_runs')->with('statusFailure', "Test set doesn't exist");
        }
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();

        // Get testcases ordered by Suite and Overview id
        $testCases = $run->testCases()
                        ->join('TestCase', 'TestCase.TestCaseOverview_id', '=', 'TestCase.TestCaseOverview_id')
                        ->orderBy('TestCase.TestSuite_id')
                        ->orderBy('TestCase.TestCaseOverview_id')
                        ->get();

        // Get only suites of assigned testcases
        $fillteredSuite = collect();
        foreach ($testCases as $testCase) {
            if (!$fillteredSuite->contains('TestSuite_id', $testCase->testCaseOverview->TestSuite_id)) {
                $fillteredSuite->push($testCase->testCaseOverview->testSuite);
            }
        }
        // Get overview page
        if ($testCaseId == null) {
            return view('runs/testRunExecutionOverview')
                ->with('testRun', $run)
                ->with('testSuites', $fillteredSuite)
                ->with('testCases', $testCases)
                ->with('sidemenuToogle', true);
        }
        // Get detailed page
        else {
            $selectedTestCase = $run->testCases->where('TestCase_id', $testCaseId)->first();
        }

        return view('runs/testRunExecution')
            ->with('testRun', $run)
            ->with('testSuites', $fillteredSuite)
            ->with('selectedTestCase', $selectedTestCase)
            ->with('testCases', $testCases)
            ->with('sidemenuToogle', true);

    }

    /**
     * Update test case of test run
     *
     * @return view
     */
    public function updateTestRunTestCase(Request $request, $testRunId, $testCaseId)
    {
        $this->validate($request, [
            'duration' => 'numeric',
            'note' => 'max:1023'
        ]);
        $run = TestRun::find($testRunId);

        // Save data
        $run->testCases()->updateExistingPivot($testCaseId, ['Status' => $request->status]);

        if ($request->duration != null) {
            $run->testCases()->updateExistingPivot($testCaseId, ['ExecutionDuration' => $request->duration]);
        }

        if ($request->note != null) {
            $run->testCases()->updateExistingPivot($testCaseId, ['Note' => $request->note]);
        }

        $run->testCases()->updateExistingPivot($testCaseId, ['Author' => Auth::user()->name]);

        $run->testCases()->updateExistingPivot($testCaseId, ['LastUpdate' => date("Y-m-d H:i:s")]);

        if (isset($request->dontMove)) {
            return redirect("sets_runs/run/execution/$testRunId/testcase/$testCaseId")->with('statusSuccess', "Editing was successful");
        }
        // Move to the the next test
        if (isset($request->move)) {
            $testCases = $run->testCases()
                            ->join('TestCase', 'TestCase.TestCaseOverview_id', '=', 'TestCase.TestCaseOverview_id')
                            ->orderBy('TestCase.TestSuite_id')
                            ->orderBy('TestCase.TestCaseOverview_id')->get();

            $currentCases = TestCaseHistory::find($testCaseId);

            $currentPosition = 0;
            foreach ($testCases as $testCase) {
                if ($testCase->TestCase_id === $currentCases->TestCase_id) {
                    break;
                }
                else {
                    $currentPosition++;
                }
            }
            if ($testCases->count() == $currentPosition + 1) {
                // Don't move
                return redirect("sets_runs/run/execution/$testRunId/testcase/$testCaseId")->with('statusSuccess', "Editing was successful");
            }
            else {
                $nextCase = $testCases[$currentPosition + 1];
                return redirect("sets_runs/run/execution/$testRunId/testcase/$nextCase->TestCase_id")->with('statusSuccess', "Editing was successful");
            }
        }

    }

    /**
     * Change status of test case in test run
     *
     * @return view
     */
    public function changeStatusTestRunTestCase(Request $request, $testRunId)
    {
        $run = TestRun::find($testRunId);
        $run->testCases()->updateExistingPivot($request->testCaseId, ['Status' => $request->testStatus]);
        $run->testCases()->updateExistingPivot($request->testCaseId, ['Author' => Auth::user()->name]);
        $run->testCases()->updateExistingPivot($request->testCaseId, ['LastUpdate' => date("Y-m-d H:i:s")]);
        return redirect("sets_runs/run/execution/$testRunId/overview")->with('statusSuccess', "Test case edited");;
    }

    /**
     * Close test run
     *
     * @return view
     */
    public function closeTestRun(Request $request, $testRunId)
    {
        $run = TestRun::find($testRunId);
        $run->Status = TestRunStatus::FINISHED;
        $run->save();
        $testSetId = $run->testSet->TestSet_id;

        return redirect("sets_runs/set/detail/$testSetId")->with('statusSuccess', "Test run finished");
    }

}
