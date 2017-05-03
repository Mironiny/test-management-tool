<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Project;
use App\RequirementHistory;
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
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }
        $requirementOverview = Requirement::where('SUT_id', $projectId)->get();
        $requirements_ = collect();
        foreach ($requirementOverview as $overview) {
            $requirements_->push($overview->testRequrements()->whereNull('ActiveDateTo')->select('TestRequirement_id', 'Name','CoverageCriteria', 'RequirementDescription')
                                        ->first());
        }

        foreach ($requirements_ as $requirement) {
            $requirement['SUT_id'] = $projectId;
            if ($requirement->testCases() != null) {
                $testcase = $requirement->testCases()
                                        ->join('TestCase', 'TestCase.TestCaseOverview_id', '=', 'TestCaseHistory.TestCaseOverview_id')
                                        ->select('TestCaseHistory.TestCase_id', 'TestCase.Name')
                                        ->get();
                $requirement['TestCase'] = $testcase;
            }
        }
        return response()->json($requirements_);
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
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }

        // Validation
        if ($request->input('Name') == null || strlen($request->input('Name') > 45)) {
            return response()->json(['error' => "Requirement name error"], 400);
        }

        $requirementOverview = new Requirement;
        $requirementOverview->SUT_id =(int) $projectId;
        $requirementOverview->save();

        $requirement = new RequirementHistory;
        $requirement->Name =  $request->input('Name');
        $requirement->TestRequirementOverview_id = (int) $requirementOverview->TestRequirementOverview_id;
        $requirement->CoverageCriteria = $request->input('CoverageCriteria');
        $requirement->RequirementDescription = $request->input('RequirementDescription');
        $requirement->save();
        $requirement['SUT_id'] = (int) $requirementOverview->SUT_id;

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
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }
        $requirementOverview = Requirement::where('SUT_id', $projectId)->first();
        $requirement = $requirementOverview->testRequrements()->whereNull('ActiveDateTo')->select('TestRequirement_id', 'Name','CoverageCriteria', 'RequirementDescription')
                                    ->first();
        // $requirement = Requirement::where('TestRequirement_id', $requirementId)
        //                             ->select('TestRequirement_id', 'SUT_id', 'Name','CoverageCriteria', 'RequirementDescription')
        //                             ->first();
        if ($requirement == null) {
            return response()->json(['error' => "No requirement found"], 404);
        }
        $requirement['SUT_id'] = (int) $projectId;
        $requirement['TestCase'] = $requirement->testCases()
                                                ->join('TestCase', 'TestCase.TestCaseOverview_id', '=', 'TestCaseHistory.TestCaseOverview_id')
                                                ->select('TestCaseHistory.TestCase_id', 'TestCase.Name')
                                                ->get();

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
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }
        $requirementOverview = Requirement::find($requirementId);
        if ($requirementOverview == null) {
            return response()->json(['error' => "Requirement not find"], 400);
        }

        $requirement = $requirementOverview->testRequrements()->whereNull('ActiveDateTo')->select('TestRequirement_id', 'Name','CoverageCriteria', 'RequirementDescription')
                                    ->first();
        $requirement->ActiveDateTo = date("Y-m-d H:i:s");
        $requirement->save();

        $requirementNew = new RequirementHistory;
        $requirementNew->TestRequirementOverview_id = $requirementId;
        if ($request->input('Name') != null) {
            $requirementNew->Name = $request->input('Name');
        }
        else {
            $requirementNew->Name = $requirement->Name;
        }
        $requirementNew->CoverageCriteria = $request->input('CoverageCriteria');
        $requirementNew->RequirementDescription = $request->input('RequirementDescription');
        $requirementNew->save();
        $requirementNew['SUT_id'] = (int) $projectId;
        return response()->json($requirementNew, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectId, $requirementId)
    {
        $project = Commons::checkProjectAndRights($projectId);
        if (!$project instanceof Project) {
            return $project;
        }
        $requirement = RequirementHistory::find($requirementId);
        if ($requirement == null) {
            return response()->json(['error' => "Requirement not find"], 400);
        }
        $requirement->ActiveDateTo = date("Y-m-d H:i:s");
        $requirement->save();
        return response()->json(['success' => "Deleted"], 200);
    }

}
