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
    public function index(Request $request)
    {
        return view('projects/projects');
    }

    /**
     * Show (render) the project creation page.
     *
     * @return view
     */
    public function createProjectForm(Request $request)
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
            'description' => 'max:255',
            'testDescription' => 'max:255'
        ]);

        $project = new Project;
        $project->Name = $request->name;
        $project->ProjectDescription = $request->description;
        $project->TestingDescription = $request->testDescription;

        $project->save();
        return redirect('projects')->with('statusSuccess', trans('projects.successCreateProject'));
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
}
