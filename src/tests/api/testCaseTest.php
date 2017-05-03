<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class testCaseTest extends TestCase
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
             ->json('POST', '/api/v1/testcases', ['TestSuite_id' => 1,
                                                'Name' => 'TestCase1',
                                                'IsManual' => '0',
                                                'TestCaseDescription' => 'SomeDescription',
                                                ])
            ->assertResponseStatus(201)
            ->seeJson([
                'TestSuite_id' => 1,
                'TestCase_id' => 1,
                'Name' => 'TestCase1',
                'TestCaseDescription' => 'SomeDescription'
            ]);

        $this->seeInDatabase('TestCaseHistory', [
            'TestCase_id' => 1
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
             ->get('/api/v1/testcases')
             ->assertResponseStatus(200)
             ->seeJsonStructure([
                 '*' => [
                     'TestSuite_id', 'TestCase_id', 'Name'
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
             ->get('/api/v1/testcases/1')
             ->assertResponseStatus(200)
             ->seeJson([
                 'TestSuite_id' => 1,
                 'TestCase_id' => 1,
                 'Name' => 'TestCase1',
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
             ->json('PUT', '/api/v1/testcases/1', ['TestCaseDescription' => 'Dessc'])
             ->assertResponseStatus(201)
             ->seeJson([
                'TestSuite_id' => 1,
                'Name' => 'TestCase1'
            ]);

        $this->seeInDatabase('TestCaseHistory', [
            'TestCase_id' => 2,
            'TestCaseDescription' => 'Dessc'
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
             ->json('DELETE', '/api/v1/testcases/1')
             ->assertResponseStatus(200)
            ->seeJson([
                'success' => 'Deleted'
            ]);

        Artisan::call('migrate:reset');
    }

}
