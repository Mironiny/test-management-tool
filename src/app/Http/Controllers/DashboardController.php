<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;
use App\Requirement;
use App\TestCase;
use App\TestSet;
use Charts;
use App\Enums\TestRunStatus;
use App\Enums\TestCaseStatus;

class DashboardController extends Controller
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
     * Get pie chart
     *
     * @return \Illuminate\Http\Response
     */
    private function getPieRequirementChart($covered, $notCovered) {
        return Charts::create('pie', 'highcharts')
                  ->title('Requirements coverage')
                  ->labels(['Covered', 'Not covered'])
                  ->values([$covered, $notCovered])
                  ->colors(['#00ff00', '#ff0000'])
                //   ->dimensions(600, 300)
                  ->responsive(true);
    }

    /**
     * Get bar chart
     *
     * @return \Illuminate\Http\Response
     */
    private function getBarRequirementChart($names, $counts) {
        return Charts::create('bar', 'highcharts')
                  ->title('Requirements coverage detail')
                  ->elementLabel('TestCase')
                  ->labels($names)
                  ->values($counts)
                  ->colors(['#9ab5ec'])
                  ->dimensions(1000,500)
                  ->responsive(true);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedProject = $request->session()->get('selectedProject');
        if (Project::all()->count() < 1) {
            return view('dashboards/dashboard');
        }

        $requirements = Requirement::where('SUT_id', $selectedProject)
                                    ->whereNull('ActiveDateTo')
                                    ->orderBy('ActiveDateFrom', 'asc')
                                    ->get();

        $covered = 0;
        $notCovered = 0;
        $names = array();
        $counts = array();
        if ($requirements->count() > 0) {
            foreach ($requirements as $requirement) {
                if ($requirement->testCases->count() > 0) {
                    $covered++;
                }
                else {
                    $notCovered++;
                }
                array_push($names, $requirement->Name);
                array_push($counts, $requirement->testCases->count());
            }
        }
        $pieRequirementsChart = $this->getPieRequirementChart($covered, $notCovered);
        $barRequirementsChart = $this->getBarRequirementChart($names, $counts);

        if (Project::all()->count() > 1) {
            $testSets = TestSet::where('SUT_id', $selectedProject)
                                        ->whereNull('ActiveDateTo')
                                        ->orderBy('ActiveDateFrom', 'asc')
                                        ->get();
        }

        $names = array();
        $notTested = array();
        $pass = array();
        $fail = array();
        $block = array();
        foreach ($testSets as $testSet) {
            $testRuns = $testSet->testRuns->where('Status', TestRunStatus::FINISHED);

            $passed = 0;
            $failed = 0;
            $blocked = 0;
            $_notTested = 0;

            if ($testRuns != null && $testRuns->count() > 0) {
                $testRun = $testRuns[$testRuns->count() - 1];

                foreach ($testRun->testCases as $testCase) {
                    if ($testCase->pivot->Status == TestCaseStatus::PASS) {
                        $passed++;
                    }
                    else if ($testCase->pivot->Status == TestCaseStatus::FAIL) {
                        $failed++;
                    }
                    else if ($testCase->pivot->Status == TestCaseStatus::BLOCKED) {
                        $blocked++;
                    }
                    else if ($testCase->pivot->Status == TestCaseStatus::NOT_TESTED) {
                        $_notTested++;
                    }
                }

            }
            array_push($names, $testSet->Name);
            array_push($pass, $passed);
            array_push($fail, $failed);
            array_push($block, $blocked);
            array_push($notTested, $_notTested);
        }

        // var_dump($pass);
        // var_dump($fail);
        // var_dump($block);
        // return;

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Test set progress")
            ->dimensions(0, 400) // Width x Height
            // ->template("material")
        ->colors(['#efae4e', '#00ff00', '#ff0000', '#000000'])
        ->dataset('Not tested', $notTested)
        ->dataset('Pass', $pass)
        ->dataset('Fail', $fail)
        ->dataset('Blocked', $block)
        ->labels($names);


        return view('dashboards/dashboard', ['pieRequirementsChart' => $pieRequirementsChart, 'barRequirementsChart' => $barRequirementsChart, 'testRunChart' => $chart]);
    }


}
