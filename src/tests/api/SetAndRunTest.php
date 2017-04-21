<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetAndRunTest extends TestCase
{

    protected static $user;
    protected static $testSuite;
    protected static $tests;

    public function setUp()
    {
        parent::setUp();

        if (is_null(self::$user)) {
            Artisan::call('migrate');
            self::$user =  factory(App\User::class)->create();
            self::$testSuite = factory(App\TestSuite::class, 2)
                                ->create()
                                ->each(function ($u) {
                                    $u->testCases()->save(factory(App\TestCaseOverview::class)->create());
                                });
            self::$tests =  factory(App\TestCase::class, 2)->create();
        }
    }

    /**
    * Create project for user.
    *
    * @return void
    */
    public function testCreateProjectForUser()
    {
        $this->actingAs(self::$user, 'api')
                ->json('POST', '/api/v1/projects', ['Name' => 'SuperProject',
                                                'ProjectDescription' => 'SuperGreatProject'
                                                ])
                ->seeJson([
                    'SUT_id' => 1,
                    'Name' => 'SuperProject',
                    'ProjectDescription' => 'SuperGreatProject'
                ]);

        $this->seeInDatabase('SUT', [
            'SUT_id' => 1,
            'Name' => 'SuperProject',
            'ProjectDescription' => 'SuperGreatProject'
        ]);

    }

    /**
     * Test POST request.
     * api/v1/projects/{projectId}/testsets
     *
     * @return void
     */
    public function testPostSet()
    {
        $this->actingAs(self::$user, 'api')
             ->json('POST', '/api/v1/projects/1/testsets', ['Name' => 'set1',
                                                    "Author" => "meee",
                                                    "TestSetDescription" => "description",
                                                    "TestCases" => [1]
                                                ])
            ->assertResponseStatus(201)
            ->seeJson([
                'TestSet_id' => 1,
                'SUT_id' => 1,
                'Name' => 'set1',
                'TestSetDescription' => 'description'
            ]);

        $this->seeInDatabase('TestSet', [
            'SUT_id' => 1,
            'TestSet_id' => 1,
            'Name' => 'set1',
            'TestSetDescription' => 'description'
        ]);

        $this->seeInDatabase('TestCase_has_TestSet', [
            'TestCase_id' => 1,
            'TestsSet_id' => 1,
        ]);

    }

    /**
     * Test GET all request.
     * api/v1/projects/{projectId}/testsets
     *
     * @return void
     */
    public function testGetAllSet()
    {
        $this->actingAs(self::$user, 'api')
             ->get('/api/v1/projects/1/testsets')
             ->assertResponseStatus(200)
             ->seeJsonStructure([
                 '*' => [
                     'SUT_id', 'TestSet_id', 'Name', 'TestCase' => [
                         '*' => [
                             'TestCase_id', 'Name'
                         ]
                     ]
                 ]
            ]);

    }

    /**
     * Test GET by id request.
     * /api/v1/projects/{projectId}/testsets/{setId}
     *
     * @return void
     */
    public function testGetByIdSet()
    {
        $this->actingAs(self::$user, 'api')
             ->get('/api/v1/projects/1/testsets/1')
             ->assertResponseStatus(200)
             ->seeJson([
                 'TestSet_id' => 1,
                 'SUT_id' => 1,
                 'Name' => 'set1',
                 'TestSetDescription' => 'description'
             ]);
    }

    /**
     * Test PUT request.
     * /api/v1/projects/{projectId}/testsets/{setId}
     *
     * @return void
     */
    public function testUpdateSet()
    {
        $this->actingAs(self::$user, 'api')
             ->json('PUT', '/api/v1/projects/1/testsets/1', ['Name' => 'updatedSet1',
                 'TestSetDescription' => 'Updated description'])
             ->assertResponseStatus(201)
             ->seeJson([
                 'TestSet_id' => 1,
                 'SUT_id' => 1,
                 'Name' => 'updatedSet1',
                 'TestSetDescription' => 'Updated description'
            ]);

        $this->seeInDatabase('TestSet', [
            'SUT_id' => 1,
            'TestSet_id' => 1,
            'Name' => 'updatedSet1',
            'TestSetDescription' => 'Updated description'
        ]);

        $this->dontSeeInDatabase('TestSet', [
            'SUT_id' => 1,
            'TestSet_id' => 1,
            'Name' => 'set1',
            'TestSetDescription' => 'description'
        ]);
    }

    /**
     * Test DELETE request.
     * /api/v1/projects/{projectId}/testsets/{setId}
     *
     * @return void
     */
    public function testDeleteSet()
    {
        $this->actingAs(self::$user, 'api')
             ->json('DELETE', '/api/v1/projects/1/testsets/1')
             ->assertResponseStatus(200)
            ->seeJson([
                'success' => 'Deleted'
            ]);
    }

    /**
     * Test POST request.
     * /api/v1/projects/{projectId}/testsets/{setId}/testruns
     *
     * @return void
     */
    public function testPostRun()
    {
        $this->actingAs(self::$user, 'api')
             ->json('POST', '/api/v1/projects/1/testsets/1/testruns')
             ->assertResponseStatus(201)
             ->seeJson([
                'TestSet_id' => 1,
                'TestRun_id' => 1,
                'Status' => 'Running'
            ]);

        $this->seeInDatabase('TestRun', [
            'TestRun_id' => 1,
            'TestSet_id' => 1,
            'Status' => 'Running'
        ]);

        $this->seeInDatabase('TestRun_has_TestCase', [
            'TestCase_id' => 1,
            'TestRun_id' => 1,
            'Status' => 'Not tested'
        ]);

    }

    /**
     * Test GET all request.
     * api/v1/projects/{projectId}/testsets
     *
     * @return void
     */
    public function testGetAllRun()
    {
        $this->actingAs(self::$user, 'api')
             ->get('/api/v1/projects/1/testsets/1/testruns')
             ->assertResponseStatus(200)
             ->seeJsonStructure([
                 '*' => [
                     'TestRun_id', 'TestSet_id', 'Status', 'TestCase' => [
                         '*' => [
                             'TestCase_id', 'Name', 'Status'
                         ]
                     ]
                 ]
            ]);

    }

    /**
     * Test PUT request.
     * /api/v1/projects/{projectId}/testsets/{setId}/testruns/{runId}
     *
     * @return void
     */
    public function testUpdateRun()
    {
        $this->actingAs(self::$user, 'api')
             ->json('PUT', '/api/v1/projects/1/testsets/1/testruns/1', ['Status' => 'Finished'])
             ->assertResponseStatus(201)
             ->seeJson([
                 'TestSet_id' => 1,
                 'TestRun_id' => 1,
                 'Status' => 'Finished'
            ]);

        $this->seeInDatabase('TestRun', [
            'TestRun_id' => 1,
            'TestSet_id' => 1,
            'Status' => 'Finished'
        ]);
    }

    /**
     * Test DELETE request.
     * /api/v1/projects/{projectId}/testsets/{setId}/testruns/{runId}
     *
     * @return void
     */
    public function testDeleteRun()
    {
        $this->actingAs(self::$user, 'api')
             ->json('DELETE', '/api/v1/projects/1/testsets/1/testruns/1')
             ->assertResponseStatus(200)
            ->seeJson([
                'success' => 'Deleted'
            ]);
    }

    /**
     * Test PUT request.
     * /api/v1/projects/{projectId}/testsets/{setId}/testruns/{runId}/testcase/{testCaseID}
     *
     * @return void
     */
    public function testUpdateRunTestCaseStatus()
    {
        $this->actingAs(self::$user, 'api')
             ->json('PUT', '/api/v1/projects/1/testsets/1/testruns/1/testcase/1', ['Status' => 'Fail'])
             ->assertResponseStatus(200)
             ->seeJson([
                 'TestCase_id' => 1,
                 'TestRun_id' => 1,
            ]);

        $this->seeInDatabase('TestRun_has_TestCase', [
            'TestRun_id' => 1,
            'TestCase_id' => 1,
            'Status' => 'Fail'
        ]);
    }

    /**
     * Test POST request.
     * api/v1/projects/{projectId}/testsets
     *
     * @return void
     */
    public function testPostSet11()
    {
        Artisan::call('migrate:reset');
    }
}
