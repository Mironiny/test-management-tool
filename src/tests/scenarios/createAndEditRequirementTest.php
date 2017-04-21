<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\TestSuite;

class createAndEditRequirementTest extends TestCase
{
    protected static $user;
    protected static $testSuite;
    protected static $tests;

    public function setUp()
    {
        parent::setUp();

        if (is_null(self::$user)) {
            Artisan::call('migrate');
            self::$testSuite = factory(App\TestSuite::class, 2)
                                ->create()
                                ->each(function ($u) {
                                    $u->testCases()->save(factory(App\TestCaseOverview::class)->create());
                                });
            self::$user = factory(App\User::class)->create();
            self::$tests =  factory(App\TestCase::class, 2)->create();
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
             ->click('#newProject')
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
             ->click('#newRequirement')
             ->seePageIs('/requirements/create')
             ->type('newRequirement', 'name')
             ->type('requirementDescription', 'description')
             ->press('submit');

             $this->seeInDatabase('TestRequirement', [
                     'Name' => 'newRequirement',
                     'RequirementDescription' => 'requirementDescription'
                 ]);

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateRequirementWithoutName()
    {
        // $user = factory(App\User::class)->create();
        $this->actingAs(self::$user)
             ->visit('/requirements')
             ->seePageIs('/requirements')
             ->click('#newRequirement')
             ->seePageIs('/requirements/create')
             ->type('requirementDescriptionNew', 'description')
             ->press('submit');

             $this->dontSeeInDatabase('TestRequirement', [
                     'RequirementDescription' => 'requirementDescriptionNew'
                 ]);

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testOverviewContainsRequirements()
    {
        // $user = factory(App\User::class)->create();
        $this->actingAs(self::$user)
             ->visit('/requirements')
             ->seePageIs('/requirements')
             ->see('newRequirement');

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEditRequirement()
    {
        // $user = factory(App\User::class)->create();
        $this->actingAs(self::$user)
             ->visit('/requirements')
             ->seePageIs('/requirements')
             ->see('newRequirement')
             ->click('newRequirement')
             ->type('requirementDescriptionEdited', 'description')
             ->press('submit');

        $this->seeInDatabase('TestRequirement', [
            'Name' => 'newRequirement',
            'RequirementDescription' => 'requirementDescriptionEdited'
        ]);

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCoverRequirement()
    {
        // $user = factory(App\User::class)->create();
        $this->actingAs(self::$user)
             ->visit('/requirements')
             ->seePageIs('/requirements')
             ->see('newRequirement')
             ->click('newRequirement');

        $this->call('POST', '/requirements/cover/1', ['testcases[]' => '[1,2]']);

        $this->visit('/requirements/detail/1')
            ->see(self::$testSuite[0]->testCases()->where('TestCaseOverview_id', 1)->first()->Name);

        Artisan::call('migrate:reset');

    }


}
