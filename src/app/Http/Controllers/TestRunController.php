<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Project;
use App\TestSet;
use App\TestRun;
use App\TestSuite;
use App\TestCase;
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

        if (Project::all()->count() > 1) {
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

        if (Project::all()->count() > 1) {
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
                $set->TestCases()->attach($testCase);
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
     * Update test cases of test set.
     *
     * @return view
     */
    public function updateTestCasesSet(Request $request, $id)
    {
        $coveredTestCases = TestSet::find($id)->TestCases()->get();
        $selectedTestcases = $request->testcases;

        // Adding to database
        foreach ($selectedTestcases as $selectedTestCase ) {
            if (!$coveredTestCases->contains('TestCase_id', $selectedTestCase)) {
                TestSet::find($id)->TestCases()->attach($selectedTestCase);
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
    public function renderSetDetail($id)
    {
        $set = TestSet::find($id);
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();
        $runs = $set->testRuns->sortByDesc('ActiveDateFrom');
        return view('runs/testSetDetail')
            ->with('set', $set)
            ->with('testSuites', $testSuites)
            ->with('runs', $runs);
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
        return redirect("sets_runs/run/execution/$run->TestRun_id/testcase");

    }

    /**
     * Render test run detail
     *
     * @return view
     */
    public function renderTestRunDetail(Request $request, $testRunId, $testCaseId = null)
    {
        $run = TestRun::find($testRunId);
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();
        $testCases = $run->testCases()->get();

        $collection = collect();
        foreach ($testCases as $testCase) {
            if (!$collection->contains('TestSuite_id', $testCase->testSuite->TestSuite_id)) {
                $collection->push($testCase->testSuite);
            }
        }
        if ($testCaseId == null) {
            $selectedTestCase = $testCases->first();
        }
        else {
            $selectedTestCase = $run->testCases->where('TestCase_id', $testCaseId)->first();
        }

        return view('runs/testRunExecution')
            ->with('testRun', $run)
            ->with('testSuites', $collection)
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
            $testCases = $run->testCases()->get();
            $currentCases = TestCase::find($testCaseId);

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
