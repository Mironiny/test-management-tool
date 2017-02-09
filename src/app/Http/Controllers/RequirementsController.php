<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
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

    private function getActiveRequirementsBySelectedProject($selectedProject)
    {
        return Requirement::where('SUT_id', $selectedProject)
                                    ->whereNull('ActiveDateTo')
                                    ->orderBy('ActiveDateFrom', 'desc')
                                    ->get();
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
                                        ->orderBy('ActiveDateFrom', 'desc')
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
            'RequirementDescription' => 'max:255'
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
        $requirements = $this->getActiveRequirementsBySelectedProject($selectedProject);
        if (sizeof($requirements) < $id) {
            return redirect('requirements')->with('statusFailure', trans('requirements.requirementNotExists'));
        }
        $requirementDetail = $requirements[$id - 1];

        return view('requirements/requirementDetail')->with('requirementDetail', $requirementDetail);
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
            'description' => 'max:255'
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


}
