<?php

namespace App\Http\Controllers\api\v1;

class Commons {

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

}

 ?>
