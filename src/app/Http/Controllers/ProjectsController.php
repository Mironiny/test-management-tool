<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectsController extends Controller
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
     * Show (render) the project page.
     *
     * @return view
     */
    public function index()
    {
        return view('projects/projectsIndex');
    }

    /**
     * Show (render) the project creation page.
     *
     * @return view
     */
    public function createProjectForm()
    {
        return view('projects/createProject');
    }

    /**
     * Store project to DB.
     *
     * @return view
     */
    public function storeProject(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:45',
            'description' => 'max:1023',
            'testDescription' => 'max:1023'
        ]);

        $project = new Project;
        $project->Name = $request->name;
        $project->ProjectDescription = $request->description;
        $project->TestingDescription = $request->testDescription;

        $project->save();
        return redirect('projects')->with('statusSuccess', trans('projects.successCreateProject'));
    }

    /**
     * Edit project in DB.
     *
     * @return view
     */
    public function updateProject(Request $request, $id)
    {
        $this->validate($request, [
            // 'name' => 'required|max:45',
            'description' => 'max:255',
            'testDescription' => 'max:255'
        ]);

        $projectDetail = Project::find($id);
        if ($projectDetail == null) {
            abort(404);
        }

        $projectDetail->ProjectDescription = $request->description;
        $projectDetail->TestingDescription = $request->testDescription;

        $projectDetail->save();
        return redirect('projects')->with('statusSuccess', trans('projects.successEditedProject'));
    }

    /**
     * Terminate project in DB.
     *
     * @return view
     */
    public function terminateProject(Request $request, $id)
    {
        $projectDetail = Project::find($id);
        if ($projectDetail == null) {
            abort(404);
        }

        $projectDetail->ActiveDateTo = date("Y-m-d H:i:s");

        $projectDetail->save();
        return redirect('projects')->with('statusSuccess', trans('projects.successTerminatedProject'));
    }

    /**
     * Change actually selected project.
     *
     * @return void
     */
    public function changeProject(Request $request)
    {
        $request->session()->put('selectedProject', $request->id);
        return redirect($request->url);
    }

    /**
     * Show (render) the project detail page.
     *
     * @return view
     */
    public function renderProjectDetail($id)
    {
        $projects = Project::whereNull('ActiveDateTo')->get();
        if (sizeof($projects) < $id) {
            return redirect('projects')->with('statusFailure', trans('projects.projectNotExists'));
        }
        $projectDetail = $projects[$id - 1];
        return view('projects/ProjectsDetail')->with('projectDetail', $projectDetail);
    }
}
