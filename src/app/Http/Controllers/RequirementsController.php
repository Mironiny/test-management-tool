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

    /**
     * Show (render) the requirements page.
     *
     * @return view
     */
    public function index(Request $request)
    {
        $selectedProject = $request->session()->get('selectedProject');
        if (Project::all()->count() > 1) {
            $requirements = Project::find($selectedProject)->requirements->sortBy('ActiveDateFrom')->all();
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
        $requirement = new Requirement;
        $requirement->Name = $request->name;
        if ($request->session()->get('selectedProject') == 0) {
            return redirect('requirements')->with('statusFailure', trans('requirements.successCreateRequirement'));
        }
        $requirement->SUT_id = $request->session()->get('selectedProject');
        $requirement->ActiveDateFrom = date("Y-m-d H:i:s");
        $requirement->RequirementDescription = $request->description;
        $requirement->save();

        return redirect('requirements')->with('statusSuccess', trans('requirements.failureCreateRequirementNotSelectedProject'));
    }
}
