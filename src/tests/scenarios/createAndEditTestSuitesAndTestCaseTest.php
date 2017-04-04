<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class createAndEditTestSuitesAndTestCaseTest extends TestCase
{
    /**
     * Create test suite. Test suite should be created.
     *
     * @return void
     */
    public function testCreateTestSuite()
    {
        Artisan::call('migrate');
        $user = factory(App\User::class)->create();

        $this->actingAs($user)
             ->visit('/library')
             ->seePageIs('/library')
             ->click('New test suite')
             ->seePageIs('/library/testsuite/create')
             ->type('TestSuite1', 'name')
             ->type('someDescription', 'description')
             ->type('someGoals', 'goals')
             ->press('submit');

        $this->seeInDatabase('TestSuite', [
                'Name' => 'TestSuite1',
                'TestSuiteDocumentation' => 'someDescription',
                'TestSuiteGoals' => 'someGoals'
            ]);
    }

    /**
     * Create testsuite without name. Testsuite shouldnt be created.
     *
     * @return void
     */
    public function testCreateTestSuiteWithoutName()
    {
        $user = factory(App\User::class)->create();

        $this->actingAs($user)
             ->visit('/library')
             ->seePageIs('/library')
             ->click('New test suite')
             ->seePageIs('/library/testsuite/create')
             ->type('someFailDescription', 'description')
             ->type('someFailGoals', 'goals')
             ->press('submit');

        $this->dontSeeInDatabase('TestSuite', [
                'TestSuiteDocumentation' => 'someFailDescription',
                'TestSuiteGoals' => 'someFailGoals'
            ]);
    }

    /**
     * Check if overview page contains test suite created above.
     *
     * @return void
     */
    public function testCheckIfOverviewContainsSuite()
    {
        $user = factory(App\User::class)->create();

        $this->actingAs($user)
             ->visit('/library')
             ->see('TestSuite1')
             ->assertViewHas('testSuites');

    }

    /**
     * Try to edit decription of test suite.
     *
     * @return void
     */
    public function testEditTestSuite()
    {
        $user = factory(App\User::class)->create();

        $this->actingAs($user)
             ->visit('/library')
             ->click('TestSuite1')
             ->type('new test suite description', 'description')
             ->type('new goal test suite', 'goals')
             ->press('submit');

        $this->seeInDatabase('TestSuite', [
                'Name' => 'TestSuite1',
                'TestSuiteDocumentation' => 'new test suite description',
                'TestSuiteGoals' => 'new goal test suite'
            ]);

    }

    /**
     * Create test case without detail. Test case should be created.
     *
     * @return void
     */
    public function testCreateTestCaseWithoutDetails()
    {
        $user = factory(App\User::class)->create();

        $this->actingAs($user)
             ->visit('/library')
             ->click('New test case')
             ->seePageIs('/library/testcase/create')
             ->type('TestCase1', 'name1')
             ->select('1', 'testSuite1')
             ->press('submit');

        $this->seeInDatabase('TestCase', [
                'Name' => 'TestCase1',
                'TestSuite_id' => '1'
            ]);
    }

    /**
     * Create test case without detail. Test case should be created.
     *
     * @return void
     */
    public function testCreateTestCaseWithDetails()
    {
        $user = factory(App\User::class)->create();

        $this->actingAs($user)
             ->visit('/library')
             ->click('New test case')
             ->seePageIs('/library/testcase/create')
             ->type('TestCase2', 'name1')
             ->select('1', 'testSuite1')
             ->type('someDecription','description1')
             ->type('somePrefixes','prefixes1')
             ->type('someSteps','steps1')
             ->type('someResult','expectedResult1')
             ->type('someSuffixes','suffixes1')
             ->press('submit');

        $this->seeInDatabase('TestCase', [
                'Name' => 'TestCase2',
                'TestSuite_id' => '1',
                'TestCaseDescription' => 'someDecription',
                'TestCasePrefixes' => 'somePrefixes',
                'TestSteps' => 'someSteps',
                'ExpectedResult' => 'someResult',
                'TestCaseSuffixes' => 'someSuffixes'
            ]);
    }

    /**
     * Create test case without name. Test case should not be created.
     *
     * @return void
     */
    public function testCreateTestCaseWithoutName()
    {
        $user = factory(App\User::class)->create();

        $this->actingAs($user)
             ->visit('/library')
             ->click('New test case')
             ->seePageIs('/library/testcase/create')
             ->type('someRandomDecription', 'description1')
             ->type('someRandomPrefixes','prefixes1')
             ->type('someRandomSteps','steps1')
             ->press('submit')
             ->seePageIs('/library/testcase/create');


        $this->dontSeeInDatabase('TestCase', [
                'TestCaseDescription' => 'someRandomDecription',
                'TestCasePrefixes' => 'someRandomPrefixes'
         ]);

     }

     /**
      * Check if overview page contains test case created above.
      *
      * @return void
      */
     public function testCheckIfOverviewContainsCase()
     {
         $user = factory(App\User::class)->create();

         $this->actingAs($user)
              ->visit('/library')
              ->see('TestCase1')
              ->assertViewHas('testCases');

     }

     /**
      * Try to edit test case
      *
      * @return void
      */
     public function testEditTestCase()
     {
         $user = factory(App\User::class)->create();

         $this->actingAs($user)
              ->visit('/library')
              ->click('TestCase1')
              ->seePageIs('/library/testcase/detail/1')
              ->type('someNewDecription','description')
              ->type('someNewPrefixes','prefixes')
              ->type('someNewSteps','steps')
              ->type('someNewResult','expectedResult')
              ->type('someNewSuffixes','suffixes')
              ->press('submit');

         $this->seeInDatabase('TestCase', [
                 'Name' => 'TestCase1',
                 'TestSuite_id' => '1',
                 'TestCaseDescription' => 'someNewDecription',
                 'TestCasePrefixes' => 'someNewPrefixes',
                 'TestSteps' => 'someNewSteps',
                 'ExpectedResult' => 'someNewResult',
                 'TestCaseSuffixes' => 'someNewSuffixes'
             ]);

        Artisan::call('migrate:reset');

     }

}
