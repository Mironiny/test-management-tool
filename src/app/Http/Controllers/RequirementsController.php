<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\TestSuite;
use App\TestCaseHistory;
use App\TestCase;
use App\RequirementHistory;
use App\Requirement;
use Lang;

class RequirementsController extends Controller
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
     * Show (render) the requirements page.
     *
     * @return view
     */
    public function index(Request $request)
    {
        $selectedProject = $request->session()->get('selectedProject');
        if (Project::all()->count() > 0) {
            $requirementsOverview = Requirement::where('SUT_id', $selectedProject)
                                                        ->whereNull('ActiveDateTo')
                                                        ->orderBy('ActiveDateFrom', 'asc')
                                                        ->get();

            return view('requirements/requirements')
                // ->with('requirements', $requirements)
                ->with('requirementsOverview', $requirementsOverview);
        }
        else {
            return view('requirements/requirements');
        }
    }

    /**
     * Show (render) the requirements create page.
     *
     * @return view
     */
    public function createRequirementForm(Request $request)
    {
        return view('requirements/createRequirement');
    }

    /**
     * Store requirement to DB.
     *
     * @return view
     */
    public function storeRequirement(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:45',
            'description' => 'max:1023'
        ]);

        $requirementOverview = new Requirement;
        if ($request->session()->get('selectedProject') == 0) {
            return redirect('requirements')->with('statusFailure', trans('requirements.failureCreateRequirementNotSelectedProject'));
        }
        $requirementOverview->SUT_id = $request->session()->get('selectedProject');
        $requirementOverview->save();

        $requirement = new RequirementHistory;
        $requirement->Name = $request->name;
        $requirement->TestRequirementOverview_id = $requirementOverview->TestRequirementOverview_id;
        $requirement->RequirementDescription = $request->description;
        $requirement->save();

        return redirect('requirements')->with('statusSuccess', trans('requirements.successCreateRequirement'));
    }

    /**
     * Show (render) the requirements detail page.
     *
     * @return view
     */
    public function renderRequirementDetail(Request $request, $id)
    {
        // $requirementDetail = Requirement::find($id);
        $selectedProject = $request->session()->get('selectedProject');

        $requirementOverview = Requirement::find($id);

        $requirementsHistory = $requirementOverview->testRequrements()
                                                    ->orderBy('ActiveDateFrom', 'DESC')
                                                    ->get();

        if ($requirementOverview->SUT_id != $selectedProject) {
             return redirect('requirements')->with('statusFailure', trans('requirements.requirementNotExists'));
        }
        else {
            $requirementDetail = $requirementOverview->testRequrements()
                                                ->whereNull('ActiveDateTo')
                                                ->first();
            $coverTestCases = RequirementHistory::find($requirementDetail->TestRequirement_id)
                                            ->testCases()
                                            ->join('TestCase', 'TestCase.TestCaseOverview_id', '=', 'TestCaseHistory.TestCaseOverview_id')
                                            ->orderBy('TestCaseHistory.TestCaseOverview_id')
                                            ->get();

            $testSuites = TestSuite::whereNull('ActiveDateTo')->get();

            return view('requirements/requirementDetail')
                ->with('requirementDetail', $requirementDetail)
                ->with('testSuites', $testSuites)
                ->with('coverTestCases', $coverTestCases)
                ->with('requirementsHistory', $requirementsHistory);
        }
    }

    /**
     * Edit requirement in DB.
     *
     * @return view
     */
    public function updateRequirement(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:45',
            'description' => 'max:1023'
        ]);
        $requirementOverview = Requirement::find($id);
        $requirementDetail = $requirementOverview->testRequrements()
                                            ->whereNull('ActiveDateTo')
                                            ->first();

        // Check if there is a change
        if ($requirementDetail->Name == $request->name && $requirementDetail->RequirementDescription == $request->description) {
            return redirect('requirements')->with('statusSuccess', "Nothing to change");
        }

        // Record will be not active
        $requirementDetail->ActiveDateTo = date("Y-m-d H:i:s");
        $requirementDetail->save();

        // Create new record
        $requirementNew = new RequirementHistory;
        if (isset($request->name)) {
            $requirementNew->Name = $request->name;
        }
        else {
            $requirementNew->Name = $requirementDetail->Name;
        }
        if (isset($request->description)) {
            $requirementNew->RequirementDescription = $request->description;
        }
        else {
            $requirementNew->RequirementDescription = $requirementDetail->RequirementDescription;
        }
        $requirementNew->TestRequirementOverview_id = $id;
        $requirementNew->save();

        // Testcase copy
        foreach ($requirementDetail->testCases()->get() as $testCase ) {
            RequirementHistory::find($requirementNew->TestRequirement_id)->TestCases()->attach($testCase->TestCase_id);
        }


        return redirect('requirements')->with('statusSuccess', trans('requirements.successEditedRequirement'));
    }

    /**
     * Edit project in DB.
     *
     * @return view
     */
    public function deleteRequirement(Request $request, $id)
    {
        $requirementDetail = Requirement::find($id);
        if ($requirementDetail == null) {
            return redirect('requirements')->with('statusFailure', trans('requirements.requirementNotExists'));
        }
        $requirementDetail->ActiveDateTo = date("Y-m-d H:i:s");

        $requirementDetail->save();
        return redirect('requirements')->with('statusSuccess', trans('requirements.deleteRequirement'));
    }

    /**
     * Get active requirements
     *
     * @return view
     */
    private function getActiveRequirementsBySelectedProject($selectedProject)
    {
        return RequirementHistory::where('SUT_id', $selectedProject)
                                    ->whereNull('ActiveDateTo')
                                    ->orderBy('ActiveDateFrom', 'desc')
                                    ->get();
    }

    /**
     * Cover test requiremet by test cases.
     *
     * @return view
     */
    public function coverRequirement(Request $request, $id)
    {
        $requirementOverview = Requirement::find($id);
        $requirementDetail = $requirementOverview->testRequrements()
                                            ->whereNull('ActiveDateTo')
                                            ->first();

        $coveredTestCases = RequirementHistory::find($requirementDetail->TestRequirement_id)->TestCases()->get();
        $selectedTestcases = ($request->testcases != null) ? $request->testcases : array();

        // Check if there is a change
        $isChange = false;
        if (!empty($selectedTestcases)) {
            foreach ($selectedTestcases as $selectedTestCase ) {
                if (!$coveredTestCases->contains('TestCaseOverview_id', $selectedTestCase)) {
                    $isChange = true;
                }
            }
        }
        foreach ($coveredTestCases as $coveredTestCase) {
            if (!in_array($coveredTestCase->TestCaseOverview_id, $selectedTestcases)) {
                $isChange = true;
            }
        }
        if (!$isChange) {
            return redirect("requirements/detail/$id")->with('statusSuccess', "Nothing to change");
        }

        // Record will be not active
        $requirementDetail->ActiveDateTo = date("Y-m-d H:i:s");
        $requirementDetail->save();

        $requirementNew = new RequirementHistory;
        $requirementNew->Name = $requirementDetail->Name;
        $requirementNew->RequirementDescription = $requirementDetail->RequirementDescription;
        $requirementNew->TestRequirementOverview_id = $requirementDetail->TestRequirementOverview_id;
        $requirementNew->save();

        // Adding to database
        if (!empty($selectedTestcases)) {
            foreach ($selectedTestcases as $selectedTestCase ) {
                $testCaseOverview = TestCase::find($selectedTestCase);
                $testCaseDetail = $testCaseOverview->testCases()->whereNull('ActiveDateTo')->first();
                RequirementHistory::find($requirementNew->TestRequirement_id)->TestCases()->attach($testCaseDetail->TestCase_id);
            }
        }
        return redirect("requirements/detail/$id")->with('statusSuccess', trans('requirements.successEditedRequirement'));
    }

    /**
     * Change actual selected version of requirement.
     *
     * @return view
     */
    public function changeVersion(Request $request, $id)
    {
        $requirementOverview = Requirement::find($id);
        $requirementOld = $requirementOverview->testRequrements()
                                            ->whereNull('ActiveDateTo')
                                            ->first();

        $requirementOld->ActiveDateTo = date("Y-m-d H:i:s");
        $requirementOld->save();

        $requirementNew = RequirementHistory::find($request->versionToChange);
        $requirementNew->ActiveDateTo = null;
        $requirementNew->save();

        return redirect("requirements/detail/$id")->with('statusSuccess', "Version of requirement changed");

    }

    /**
     * Remove version from db.
     *
     * @return view
     */
    public function removeVersion(Request $request, $id)
    {
        $requirement = RequirementHistory::find($request->versionToChangeRemove);
        $requirement->delete();

        return redirect("requirements/detail/$id")->with('statusSuccess', "Version was deleted");

    }

}
