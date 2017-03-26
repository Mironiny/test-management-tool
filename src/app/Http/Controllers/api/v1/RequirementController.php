<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Project;
use App\Requirement;

class RequirementController extends Controller
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
    public function index($projectId)
    {
        $project = $this->checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }

        // $project = Project::find($projectId);
        // if ($project == null) {
        //     return response()->json(['error' => "project not found"], 400);
        // }
        // $users = $project->users()->get();
        // if (!$users->contains('id',  Auth::user()->id)) {
        //     return response()->json(['error' => "Not rights to project"], 404);
        // }
        $requirements = Requirement::where('SUT_id', $project->SUT_id)
                                    ->whereNull('ActiveDateTo')
                                    ->orderBy('ActiveDateFrom', 'asc')
                                    ->select('TestRequirement_id', 'SUT_id', 'Name','CoverageCriteria', 'RequirementDescription')
                                    ->get();
        foreach ($requirements as $requirement) {
            $requirement['TestCase'] = $requirement->testCases()->select('TestCase.TestCase_id', 'TestCase.Name')->get();
        }
        return response()->json($requirements);
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
    public function store(Request $request, $projectId)
    {
        $project = $this->checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }

        // Validation
        if ($request->input('Name') == null || strlen($request->input('Name') > 45)) {
            return response()->json(['error' => "Requirement name error"], 400);
        }
        if ($request->input('SUT_id') == null || Project::find($request->input('SUT_id') == null)) {
            return response()->json(['error' => "SUT id don't exists"], 400);
        }

        $requirement = new Requirement;
        $requirement->Name = $request->input('Name');
        $requirement->SUT_id = $request->input('SUT_id');
        $requirement->CoverageCriteria = $request->input('CoverageCriteria');
        $requirement->RequirementDescription = $request->input('RequirementDescription');
        $requirement->save();

        return response()->json($requirement, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($projectId, $requirementId)
    {
        $project = $this->checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }
        $requirement = Requirement::where('TestRequirement_id', $requirementId)
                                    ->select('TestRequirement_id', 'SUT_id', 'Name','CoverageCriteria', 'RequirementDescription')
                                    ->first();
        if ($requirement == null) {
            return response()->json(['error' => "Not rights to project"], 404);
        }
        $requirement['TestCase'] = $requirement->testCases()->select('TestCase.TestCase_id', 'TestCase.Name')->get();

        return response()->json($requirement);
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
    public function update(Request $request, $projectId, $requirementId)
    {
        $project = $this->checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }

        // Validation
        if ($request->input('Name') == null || strlen($request->input('Name') > 45)) {
            return response()->json(['error' => "Requirement name error"], 400);
        }
        if ($request->input('SUT_id') == null || Project::find($request->input('SUT_id') == null)) {
            return response()->json(['error' => "SUT id don't exists"], 400);
        }
        $requirement = Requirement::find($requirementId);
        if ($requirement == null) {
            return response()->json(['error' => "Requirement not find"], 400);
        }
        $requirement->Name = $request->input('Name');
        $requirement->SUT_id = $request->input('SUT_id');
        $requirement->CoverageCriteria = $request->input('CoverageCriteria');
        $requirement->RequirementDescription = $request->input('RequirementDescription');
        $requirement->save();

        return response()->json($requirement, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectId, $requirementId)
    {
        $project = $this->checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }
        $requirement = Requirement::find($requirementId);
        if ($requirement == null) {
            return response()->json(['error' => "Requirement not find"], 400);
        }
        $requirement->ActiveDateTo = date("Y-m-d H:i:s");
        $requirement->save();
        return response()->json(['success' => "Deleted"], 200);
    }

    private function checkProjectAndRights($projectId)
    {
        $project = Project::find($projectId);
        if ($project == null) {
            return response()->json(['error' => "project not found"], 400);
        }
        $users = $project->users()->get();
        if (!$users->contains('id',  Auth::user()->id)) {
            return response()->json(['error' => "Not rights to project"], 404);
        }
        return $project;
    }
}
