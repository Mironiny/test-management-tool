<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Support\Facades\Auth;
use App\Project;

class Commons {

    /**
     * Filter information to show in testcase
     *
     * @param  int  $id
     * @return testcases
     */
    public static function filterTestCase($testCases)
    {
        $data = [];

        foreach ($testCases as $testCase) {
            $entry = [
                'TestCase_id' => $testCase->TestCase_id,
                'TestSuite_id' => $testCase->TestSuite_id,
                'Name' => $testCase->Name,
                'IsManual' => $testCase->IsManual,
                'TestCasePrefixes' => $testCase->TestCasePrefixes,
                'TestSteps' => $testCase->TestSteps,
                'ExpectedResult' => $testCase->ExpectedResult,
                'TestCaseSuffixes' => $testCase->TestCaseSuffixes,
                'SourceCode' => $testCase->SourceCode,
                'TestCaseDescription' => $testCase->TestCaseDescription,
                'Note' => $testCase->Note,
                'href' => route('testcases.show', ['id' => $testCase->TestCase_id])
            ];
            $data[] = $entry;
        }
        return $data;
    }

    /**
     * Check if project exist and if user has permission.
     *
     */
    public static function checkProjectAndRights($projectId)
    {
        $project = Project::find($projectId);
        if ($project == null) {
            return response()->json(['error' => "Project not found"], 404);
        }
        $users = $project->users()->get();
        if (!$users->contains('id',  Auth::user()->id)) {
            return response()->json(['error' => "Not rights to project"], 400);
        }
        return $project;
    }

}

 ?>
