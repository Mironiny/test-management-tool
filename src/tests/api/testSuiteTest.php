<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class testSuite extends TestCase
{
    protected static $user;

    public function setUp()
    {
        parent::setUp();

        if (is_null(self::$user)) {
            Artisan::call('migrate');
            self::$user =  factory(App\User::class)->create();
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
             ->json('POST', '/api/v1/testsuites', ['Name' => 'TestSuite1',
                                                'TestSuiteGoals' => 'SomeGoals',
                                                'TestSuiteVersion' => 'Version',
                                            'TestSuiteDocumentation' => 'What should be tested'])
            ->assertResponseStatus(201)
            ->seeJson([
                'TestSuite_id' => 1,
                'Name' => 'TestSuite1',
                'TestSuiteVersion' => 'Version',
                'TestSuiteDocumentation' => 'What should be tested'
            ]);

        $this->seeInDatabase('TestSuite', [
            'TestSuite_id' => 1,
            'Name' => 'TestSuite1'
        ]);
    }

    /**
     * Test POST request.
     *
     * @return void
     */
    public function testPost2()
    {
        $this->actingAs(self::$user, 'api')
             ->json('POST', '/api/v1/testsuites', ['Name' => 'TestSuite2',
                                                'TestSuiteGoals' => 'SomeGoals2',
                                                'TestSuiteVersion' => 'Version2',
                                                'TestSuiteDocumentation' => 'What should be tested2'])
            ->assertResponseStatus(201)
            ->seeJson([
                'TestSuite_id' => 2,
                'Name' => 'TestSuite2',
                'TestSuiteVersion' => 'Version2',
                'TestSuiteDocumentation' => 'What should be tested2'
            ]);

        $this->seeInDatabase('TestSuite', [
            'TestSuite_id' => 2,
            'Name' => 'TestSuite2'
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
             ->get('/api/v1/testsuites')
             ->assertResponseStatus(200)
             ->seeJsonStructure([
                 '*' => [
                     'TestSuite_id', 'Name'
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
             ->get('/api/v1/testsuites/1')
             ->assertResponseStatus(200)
             ->seeJson([
                 'TestSuite_id' => 1,
                 'Name' => 'TestSuite1',
                 'TestSuiteVersion' => 'Version',
                 'TestSuiteDocumentation' => 'What should be tested'
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
             ->json('PUT', '/api/v1/testsuites/1', ['Name' => 'TestSuite3',
                                                'TestSuiteGoals' => 'SomeGoals3',
                                                'TestSuiteVersion' => 'Version3',
                                                'TestSuiteDocumentation' => 'What should be tested3'])
            ->assertResponseStatus(201)
            ->seeJson([
                'TestSuite_id' => 1,
                'Name' => 'TestSuite3',
                'TestSuiteVersion' => 'Version3',
                'TestSuiteDocumentation' => 'What should be tested3'
            ]);

        $this->seeInDatabase('TestSuite', [
            'TestSuite_id' => 1,
            'Name' => 'TestSuite3'
        ]);

        $this->DontSeeInDatabase('TestSuite', [
            'TestSuite_id' => 1,
            'Name' => 'TestSuite1'
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
             ->json('DELETE', '/api/v1/testsuites/1')
             ->assertResponseStatus(200)
             ->seeJson([
                'success' => 'Deleted'
            ]);

        Artisan::call('migrate:reset');
    }
}
