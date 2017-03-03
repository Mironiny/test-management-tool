<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\TestSet;
use App\TestRun;
use App\TestSuite;

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
     * Show (render) the test run page.
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
     * Show (render) the test run page.
     *
     * @return view
     */
    public function renderSetDetail($id)
    {
        $set = TestSet::find($id);
        $runs = $set->testRuns;
        return view('runs/testSetDetail')
            ->with('set', $set)
            ->with('runs', $runs);
    }

}
