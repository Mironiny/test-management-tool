<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 04.05.2017
 * Time: 18:04
 */

namespace app\Services;

use Illuminate\Http\Request;
use App\TestCaseHistory;
use App\TestCase;
use App\TestSuite;

class TestLibraryService
{
    /**
     * Get all active testSuites
     * @return mixed
     */
    public function getActiveSuites()
    {
        return TestSuite::whereNull('ActiveDateTo')->get();
    }

    /**
     * Get all active testSuites
     * @return mixed
     */
    public function getSuiteById($id)
    {
        return TestSuite::whereNull('ActiveDateTo')->where('TestSuite_id', $id)->get();
    }

    /**
     * Get all active testSuites
     * @return mixed
     */
    public function getActiveTestCases()
    {
        $testSuites = $this->getActiveSuites();
        $testCases = collect();
        foreach ($testSuites as $testSuite) {
            $colection = $testSuite->testCases()->whereNull('ActiveDateTo')->get();
            $testCases = $testCases->merge($colection);
        }
        return $testCases;
    }

    /**
     * Get all active testSuites
     * @return mixed
     */
    public function getBaseTestCaseById($id)
    {
        $testCaseOverview = TestCase::find($id);
        $testCase = $testCaseOverview
            ->testCases()
            ->whereNull('ActiveDateTo')
            ->first();

        return $testCase;
    }

    /**
     * Get all active testSuites
     * @return mixed
     */
    public function getCurrentTestCaseById($id)
    {
        $testCaseOverview = TestCase::find($id);
    //    $testCase = $this->getBaseTestCaseById($id);

        return $testCaseOverview->testCases()
            ->orderBy('ActiveDateFrom', 'DESC')
            ->get();
    }
}