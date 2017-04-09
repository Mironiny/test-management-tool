<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class projectTest extends TestCase
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
     * Test POST request.
     *
     * @return void
     */
    public function testPost1()
    {
        $this->actingAs(self::$user, 'api')
             ->json('POST', '/api/v1/projects', ['Name' => 'SuperProject',
                                                'ProjectDescription' => 'SuperGreatProject'
                                                ])
            ->assertResponseStatus(201)
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
     * Test GET all request.
     *
     * @return void
     */
    public function testGetAll()
    {
        $this->actingAs(self::$user, 'api')
             ->get('/api/v1/projects')
             ->assertResponseStatus(200)
             ->seeJsonStructure([
                 '*' => [
                     'SUT_id', 'Name'
                 ]
            ]);

    }

    /**
     * Test GET by id request.
     *
     * @return void
     */
    public function testGetById()
    {
        $this->actingAs(self::$user, 'api')
             ->get('/api/v1/projects/1')
             ->assertResponseStatus(200)
             ->seeJson([
                 'SUT_id' => 1,
                 'Name' => 'SuperProject',
                 'ProjectDescription' => 'SuperGreatProject'
             ]);
    }

    /**
     * Test PUT request.
     *
     * @return void
     */
    public function testUpdate()
    {
        $this->actingAs(self::$user, 'api')
             ->json('PUT', '/api/v1/projects/1', ['Name' => 'SuperProjectEvenBetter', 'ProjectDescription' => 'SuperGreatProjectVol2'])
             ->assertResponseStatus(201)
             ->seeJson([
                 'SUT_id' => 1,
                 'Name' => 'SuperProjectEvenBetter',
                 'ProjectDescription' => 'SuperGreatProjectVol2',
            ]);

        $this->seeInDatabase('SUT', [
            'SUT_id' => 1,
            'Name' => 'SuperProjectEvenBetter',
            'ProjectDescription' => 'SuperGreatProjectVol2',
        ]);

        $this->DontSeeInDatabase('SUT', [
            'SUT_id' => 1,
            'Name' => 'SuperProject',
            'ProjectDescription' => 'SuperGreatProject',
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
             ->json('DELETE', '/api/v1/projects/1')
             ->assertResponseStatus(200)
            ->seeJson([
                'success' => 'Deleted'
            ]);

        Artisan::call('migrate:reset');
    }
}
