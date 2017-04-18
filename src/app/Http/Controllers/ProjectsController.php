<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Project;
use App\User;
use App\Enums\ProjectRole;

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
     * Show (render) the project page - only finished projects.
     *
     * @return view
     */
    public function filterFinished()
    {
        $projectsFilter = Project::whereNotNull('ActiveDateTo')->get();
        return view('projects/projectsIndex')
            ->with('projectsFilter', $projectsFilter);
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
        $project->HwRequirements = $request->HwRequirements;
        $project->SwRequirements = $request->SwRequirements;
        $project->save();
        Auth::user()->projects()->attach($project->SUT_id);
        Auth::user()->projects()->updateExistingPivot($project->SUT_id, ['Role' => ProjectRole::OWNER]);

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
        $projectDetail->HwRequirements = $request->HwRequirements;
        $projectDetail->SwRequirements = $request->SwRequirements;

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
        $projectDetail = Project::find($id);
        $userId = Auth::user()->id;
        $logUser = $projectDetail->Users()->where('users.id', $userId)->first();
        $assignedUsers = $projectDetail->users()->get();
        $users = User::all();
    //    $logUser = Auth::user();
        return view('projects/projectsDetail')
            ->with('projectDetail', $projectDetail)
            ->with('assignedUsers', $assignedUsers)
            ->with('logUser', $logUser)
            ->with('users', $users);
    }

    public function assignUsers(Request $request, $id)
    {
         $project = Project::find($id);

         $assignedUsers = $project->users()->get();
         $selectedUsers = ($request->users != null) ? $request->users : array();

         // Adding to database
         if (!empty($selectedUsers)) {
             foreach ($selectedUsers as $selectedUser) {
                 if (!$assignedUsers->contains('id', $selectedUser)) {
                     Project::find($id)->users()->attach($selectedUser);
                     Project::find($id)->users()->updateExistingPivot($selectedUser, ['Role' =>  ProjectRole::USER]);
                 }
             }
          }

          // Deleting from db
          foreach ($assignedUsers as $assignedUser) {
              if (!in_array($assignedUser->id, $selectedUsers) && $assignedUser->pivot->Role != ProjectRole::OWNER) {
                   Project::find($id)->users()->detach($assignedUser->id);
              }
          }

         return redirect("projects/detail/$id")->with('statusSuccess', "Users changed");
    }

}
