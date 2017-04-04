<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class createAndEditProjectTest extends TestCase
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
     * If there is no project in app, no project should be created
     *
     * @return void
     */
    public function testNotSelectedProject()
    {
        $this->actingAs(self::$user)
             ->visit('/projects')
             ->seePageIs('/projects');

        $this->assertSessionHas('selectedProject', 0);
    }

    /**
     * A basic test example.
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
     * A basic test example.
     *
     * @return void
     */
    public function testCreateProjectWithoutName()
    {
        $this->actingAs(self::$user)
             ->visit('/projects')
             ->seePageIs('/projects')
             ->click('New project')
             ->seePageIs('/projects/create')
             ->type('Some new description', 'description')
             ->type('Some new test description', 'testDescription')
             ->press('submit');

        $this->dontSeeInDatabase('SUT', [
                'ProjectDescription' => 'Some new description',
                'TestingDescription' => 'Some new test description'
            ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCheckIfOverviewContainsProject()
    {
        $this->actingAs(self::$user)
             ->visit('/projects')
             ->seePageIs('/projects')
             ->see('newProject');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEditProject()
    {
        $this->actingAs(self::$user)
             ->visit('/projects/detail/1')
             ->seePageIs('/projects/detail/1')
             ->type('Some edited description', 'description')
             ->type('Some edited test description', 'testDescription')
             ->press('submit');

        $this->seeInDatabase('SUT', [
                'Name' => 'newProject',
                'ProjectDescription' => 'Some edited description',
                'TestingDescription' => 'Some edited test description'
            ]);
        Artisan::call('migrate:reset');
    }
}
