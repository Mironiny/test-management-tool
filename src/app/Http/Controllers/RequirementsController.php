<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\TestSuite;
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
        if (Project::all()->count() > 1) {
            $requirements = Requirement::where('SUT_id', $selectedProject)
                                        ->whereNull('ActiveDateTo')
                                        ->orderBy('ActiveDateFrom', 'asc')
                                        ->get();
            return view('requirements/requirements')->with('requirements', $requirements);
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

        $requirement = new Requirement;
        $requirement->Name = $request->name;
        if ($request->session()->get('selectedProject') == 0) {
            return redirect('requirements')->with('statusFailure', trans('requirements.failureCreateRequirementNotSelectedProject'));
        }
        $requirement->SUT_id = $request->session()->get('selectedProject');
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
        $requirementDetail = Requirement::find($id);
        $coverTestCases = Requirement::find($id)->testCases()->get();
        $testSuites = TestSuite::whereNull('ActiveDateTo')->get();
        // $requirements = $this->getActiveRequirementsBySelectedProject($selectedProject);
        // if (sizeof($requirements) < $id) {
        //     return redirect('requirements')->with('statusFailure', trans('requirements.requirementNotExists'));
        // }
        // $requirementDetail = $requirements[$id - 1];


        return view('requirements/requirementDetail')
            ->with('requirementDetail', $requirementDetail)
            ->with('testSuites', $testSuites)
            ->with('coverTestCases', $coverTestCases);
    }

    /**
     * Edit requirement in DB.
     *
     * @return view
     */
    public function updateRequirement(Request $request, $id)
    {
        $this->validate($request, [
            // 'name' => 'required|max:45',
            'description' => 'max:1023'
        ]);

        $requirementDetail = Requirement::find($id);
        $requirementDetail->RequirementDescription = $request->description;

        $requirementDetail->save();
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
        return Requirement::where('SUT_id', $selectedProject)
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
        $coveredTestCases = Requirement::find($id)->TestCases()->get();
        $selectedTestcases = ($request->testcases != null) ? $request->testcases : array();

        // Adding to database
        if (!empty($selectedTestcases)) {
            foreach ($selectedTestcases as $selectedTestCase ) {
                if (!$coveredTestCases->contains('TestCase_id', $selectedTestCase)) {
                    Requirement::find($id)->TestCases()->attach($selectedTestCase);
                }
            }
        }
        // Deleting from db
        foreach ($coveredTestCases as $coveredTestCase) {
            if (!in_array($coveredTestCase->TestCase_id, $selectedTestcases)) {
                Requirement::find($id)->TestCases()->detach($coveredTestCase->TestCase_id);
            }
        }
        return redirect("requirements/detail/$id")->with('statusSuccess', trans('requirements.successEditedRequirement'));
    }

}
