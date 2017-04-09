<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class requirementTest extends TestCase
{

    protected static $user;
    protected static $testSuite;

    public function setUp()
    {
        parent::setUp();

        if (is_null(self::$user)) {
            Artisan::call('migrate');
            self::$user =  factory(App\User::class)->create();
            self::$testSuite = factory(App\TestSuite::class)->create();
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
     * api/v1/projects/{projectId}/requirements
     *
     * @return void
     */
    public function testPost1()
    {
        $this->actingAs(self::$user, 'api')
             ->json('POST', '/api/v1/projects/1/requirements', ['Name' => 'SuperRequirement',
                                                    'RequirementDescription' => 'SuperDescription'
                                                ])
            ->assertResponseStatus(201)
            ->seeJson([
                'TestRequirement_id' => 1,
                'SUT_id' => 1,
                'Name' => 'SuperRequirement',
                'RequirementDescription' => 'SuperDescription'
            ]);

        $this->seeInDatabase('TestRequirement', [
            'SUT_id' => 1,
            'TestRequirement_id' => 1,
            'Name' => 'SuperRequirement',
            'RequirementDescription' => 'SuperDescription'
        ]);

    }

    /**
     * Test GET all request.
     * /api/v1/projects/{projectId}/requirements
     *
     * @return void
     */
    public function testGetAll()
    {
        $this->actingAs(self::$user, 'api')
             ->get('/api/v1/projects/1/requirements')
             ->assertResponseStatus(200)
             ->seeJsonStructure([
                 '*' => [
                     'SUT_id', 'Name', 'TestRequirement_id'
                 ]
            ]);

    }

    /**
     * Test GET by id request.
     * /api/v1/projects/{projectId}/requirements/{requirementId}
     *
     * @return void
     */
    public function testGetById()
    {
        $this->actingAs(self::$user, 'api')
             ->get('/api/v1/projects/1/requirements/1')
             ->assertResponseStatus(200)
             ->seeJson([
                 'TestRequirement_id' => 1,
                 'SUT_id' => 1,
                 'Name' => 'SuperRequirement',
                 'RequirementDescription' => 'SuperDescription'
             ]);
    }

    /**
     * Test PUT request.
     * /api/v1/projects/{projectId}/requirements/{requirementId}
     *
     * @return void
     */
    public function testUpdate()
    {
        $this->actingAs(self::$user, 'api')
             ->json('PUT', '/api/v1/projects/1/requirements/1', ['Name' => 'EvenBetterSuperRequirement',
                'RequirementDescription' => 'EvenMoreSuperDescription'])
            ->assertResponseStatus(201)
             ->seeJson([
                 'SUT_id' => 1,
                 'Name' => 'EvenBetterSuperRequirement',
                 'RequirementDescription' => 'EvenMoreSuperDescription',
            ]);

        $this->seeInDatabase('TestRequirement', [
            'SUT_id' => 1,
            'TestRequirement_id' => 1,
            'Name' => 'EvenBetterSuperRequirement',
            'RequirementDescription' => 'EvenMoreSuperDescription'
        ]);

        $this->dontSeeInDatabase('TestRequirement', [
            'SUT_id' => 1,
            'TestRequirement_id' => 1,
            'Name' => 'SuperRequirement',
            'RequirementDescription' => 'SuperDescription'
        ]);
    }

    /**
     * Test DELETE request.
     *
     * @return void
     */
    public function testDelete()
    {
        $this->actingAs(self::$user, 'api')
             ->json('DELETE', '/api/v1/projects/1/requirements/1')
             ->assertResponseStatus(200)
            ->seeJson([
                'success' => 'Deleted'
            ]);

        Artisan::call('migrate:reset');
    }
}
