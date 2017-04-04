<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class createAndEditRequirementTest extends TestCase
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
    public function testCreateRequirement()
    {
        // $user = factory(App\User::class)->create();
        $this->actingAs(self::$user)
             ->visit('/requirements')
             ->seePageIs('/requirements')
             ->click('New requirement')
             ->seePageIs('/requirements/create')
             ->type('newRequirement', 'name')
             ->type('requirementDescription', 'description')
             ->press('submit');

             $this->seeInDatabase('TestRequirement', [
                     'Name' => 'newRequirement',
                     'RequirementDescription' => 'requirementDescription'
                 ]);


    }
}
