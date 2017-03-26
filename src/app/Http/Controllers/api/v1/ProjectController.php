<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Project;
use App\Enums\ProjectRole;


class ProjectController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Auth::user()->projects()->whereNull('ActiveDateTo')->get();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        if ($request->input('Name') == null || strlen($request->input('Name') > 45)) {
            return response()->json(['error' => "SUT name error"], 400);
        }

        $project = new Project;
        $project->Name = $request->input('Name');
        $project->ProjectDescription = $request->input('ProjectDescription');
        $project->TestingDescription = $request->input('TestingDescription');
        $project->HwRequirements = $request->input('HwRequirements');
        $project->SwRequirements = $request->input('SwRequirements');
        $project->Note = $request->input('Note');
        $project->save();

        Auth::user()->projects()->attach($project->SUT_id);
        Auth::user()->projects()->updateExistingPivot($project->SUT_id, ['Role' => ProjectRole::OWNER]);

        return response()->json($project, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);
        if ($project == null) {
            return response()->json(['error' => "Project not found"], 404);
        }
        $users = $project->users()->get();
        if ($users->contains('id',  Auth::user()->id)) {
            return response()->json($project);
        }
        return response()->json(['error' => "Not rights to project"], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validation
        if ($request->input('Name') == null || strlen($request->input('Name') > 45)) {
            return response()->json(['error' => "SUT name error"], 400);
        }

        $project = Project::find($id);
        if ($project == null) {
            return response()->json(['error' => "SUT not found"], 400);
        }
        if (!$users->contains('id',  Auth::user()->id)) {
            return response()->json(['error' => "Not rights to project"], 404);
        }
        $project->Name = $request->input('Name');
        $project->ProjectDescription = $request->input('ProjectDescription');
        $project->TestingDescription = $request->input('TestingDescription');
        $project->HwRequirements = $request->input('HwRequirements');
        $project->SwRequirements = $request->input('SwRequirements');
        $project->Note = $request->input('Note');
        $project->save();

        return response()->json($project, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        if ($project == null) {
            return response()->json(['error' => "Project not found"], 404);
        }
        if (!$users->contains('id',  Auth::user()->id)) {
            return response()->json(['error' => "Not rights to project"], 404);
        }
        $project->ActiveDateTo = date("Y-m-d H:i:s");
        $project->save();
        return response()->json(['success' => "Deleted"], 200);
    }
}
