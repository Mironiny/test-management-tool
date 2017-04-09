<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class createTestSetAndRunTest extends TestCase
{

    protected static $user;
    protected static $testSuite;

    public function setUp()
    {
        parent::setUp();

        if (is_null(self::$user)) {
            Artisan::call('migrate');
            self::$testSuite = factory(App\TestSuite::class, 4)
                                ->create()
                                ->each(function ($u) {
                                    $u->testCases()->save(factory(App\TestCase::class)->create());
                                });
            self::$user =  factory(App\User::class)->create();
        }

    }

    /**
     * For project create.
     *
     * @return void
     */
    public function testCreateProject()
    {
        // $user = factory(App\User::class)->create();
        $this->actingAs(self::$user)
             ->visit('/projects')
             ->seePageIs('/projects')
             ->click('New project')
             ->seePageIs('/projects/create')
             ->type('newProject', 'name')
             ->type('Some description', 'description')
             ->type('Some test description', 'testDescription')
             ->press('submit');

        $this->assertSessionHas('selectedProject', 1);

        $this->seeInDatabase('SUT', [
                'Name' => 'newProject',
                'ProjectDescription' => 'Some description',
                'TestingDescription' => 'Some test description'
            ]);

    }

    /**
     * Basic create set test. Set should be created.
     *
     * @return void
     */
    public function testCreateSet()
    {
        $this->actingAs(self::$user)
             ->visit('/sets_runs')
             ->seePageIs('/sets_runs')
             ->click('#newSet')
             ->seePageIs('/sets_runs/set/create')
             ->type('New set name', 'name')
             ->type('New set description', 'description')
             ->select([1, 2], 'testcases[]')
             ->press('submit');

             $this->seeInDatabase('TestSet', [
                 'TestSet_id' => 1,
                 'Name' => 'New set name',
                 'TestSetDescription' => 'New set description'
             ]);

             $this->seeInDatabase('TestCase_has_TestSet', [
                 'TestCase_id' => 1,
                 'TestsSet_id' => 1
             ]);
    }

    /**
     * Check if page overview contains set.
     *
     * @return void
     */
    public function testOverviewContainsSet()
    {
        $this->actingAs(self::$user)
             ->visit('/sets_runs')
             ->seePageIs('/sets_runs')
             ->see('New set name');
    }

    /**
     * Create test set without name. It should not create.
     *
     * @return void
     */
    public function testCreateSetWithoutName()
    {
        $this->actingAs(self::$user)
             ->visit('/sets_runs')
             ->seePageIs('/sets_runs')
             ->click('#newSet')
             ->seePageIs('/sets_runs/set/create')
             ->type('desc', 'description')
             ->select([1, 2], 'testcases[]')
             ->press('submit');

             $this->dontSeeInDatabase('TestSet', [
                 'TestSet_id' => 2,
                 'TestSetDescription' => 'desc'
             ]);
    }

    /**
     * Create test set without test. It should not create
     *
     * @return void
     */
    public function testCreateSetWithoutTests()
    {
        $this->actingAs(self::$user)
             ->visit('/sets_runs')
             ->seePageIs('/sets_runs')
             ->click('#newSet')
             ->seePageIs('/sets_runs/set/create')
             ->type('SetName1', 'name')
             ->type('desc', 'description')
             ->press('submit');

             $this->dontSeeInDatabase('TestSet', [
                 'TestSet_id' => 2,
                 'Name' => 'SetName1',
                 'TestSetDescription' => 'desc'
             ]);
    }


    /**
     * Try to edit test set.
     *
     * @return void
     */
    public function testEditTestSet()
    {
        $this->actingAs(self::$user)
             ->visit('/sets_runs')
             ->seePageIs('/sets_runs')
             ->click('New set name')
             ->seePageIs('sets_runs/set/detail/1')
             ->type('New-description', 'description')
             ->press('submit');

        $this->seeInDatabase('TestSet', [
            'TestSet_id' => 1,
            'Name' => 'New set name',
            'TestSetDescription' => 'New-description'
        ]);
    }

    /**
     * Create test run.
     *
     * @return void
     */
    public function testCreateTestRun()
    {
        $this->actingAs(self::$user)
             ->visit('/sets_runs')
             ->seePageIs('/sets_runs')
             ->click('New set name')
             ->seePageIs('sets_runs/set/detail/1')
             ->press('newRunSubmit')
             ->seePageIs('/sets_runs/run/execution/1/overview');

        $this->seeInDatabase('TestRun', [
            'TestRun_id' => 1,
            'TestSet_id' => 1,
            'Status' => 'Running'
        ]);
    }

    /**
     * Test if ovewview page contains test run.
     *
     * @return void
     */
    public function testOverviewContainsRun()
    {
        $this->actingAs(self::$user)
             ->visit('/sets_runs')
             ->seePageIs('/sets_runs')
             ->click('New set name')
             ->seePageIs('sets_runs/set/detail/1')
             ->see('1')
             ->see('Running');

    }

    /**
     * Test if execution page contains test.
     *
     * @return void
     */
    public function testExecuteContainsTests()
    {
        $this->actingAs(self::$user)
             ->visit('/sets_runs/run/execution/1/overview')
             ->see(self::$testSuite[0]->testCases()->where('TestCase_id', 1)->first()->Name);
    }

    /**
     * Test change staatus of testcase from overview table.
     *
     * @return void
     */
    public function testChangeTestStatusFromOverview()
    {
        $this->actingAs(self::$user)
             ->visit('/sets_runs/run/execution/1/overview')
             ->type(1, 'testCaseId')
             ->type('Fail', 'testStatus')
             ->press('move');

             $this->seeInDatabase('TestRun_has_TestCase', [
                 'TestRun_id' => 1,
                 'TestCase_id' => 1,
                 'Status' => 'Fail'
             ]);
    }

    /**
     * Change test status from detail test case page.
     *
     * @return void
     */
    public function testChangeTestStatusFromTestDetail()
    {
        $this->actingAs(self::$user)
             ->visit('/sets_runs/run/execution/1/overview')
             ->click(self::$testSuite[1]->testCases()->where('TestCase_id', 2)->first()->Name)
             ->seePageIs('sets_runs/run/execution/1/testcase/2')
             ->type('Pass', 'status')
             ->press('dontMove');

             $this->seeInDatabase('TestRun_has_TestCase', [
                 'TestRun_id' => 1,
                 'TestCase_id' => 2,
                 'Status' => 'Pass'
             ]);

    }

    /**
     * Test change status from execution page.
     *
     * @return void
     */
    public function testChangeRunStatusFromExecute()
    {
        $this->actingAs(self::$user)
             ->visit('/sets_runs/run/execution/1/overview')
             ->click(self::$testSuite[1]->testCases()->where('TestCase_id', 2)->first()->Name)
             ->seePageIs('sets_runs/run/execution/1/testcase/2')
             ->press('finish');

             $this->seeInDatabase('TestRun', [
                 'TestRun_id' => 1,
                 'Status' => 'Finished'
             ]);
    }

    /**
     * Test change status of test run in overviw page.
     *
     * @return void
     */
    public function testChangeRunStatusFromOverview()
    {
        $this->actingAs(self::$user)
            ->visit('/sets_runs')
            ->seePageIs('/sets_runs')
            ->click('New set name')
            ->type('1', 'testRunId')
            ->type('Archived', 'status')
            ->press('changeStatus');

            $this->seeInDatabase('TestRun', [
                'TestRun_id' => 1,
                'Status' => 'Archived'
            ]);
            
        Artisan::call('migrate:reset');
    }

}
