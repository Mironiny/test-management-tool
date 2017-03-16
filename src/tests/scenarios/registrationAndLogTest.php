<?php
use Illuminate\Support\Facades\Artisan;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class registrationAndLog extends TestCase
{

    /**
     * Log in system in classic way.
     *
     * @return void
     */
    public function testRegistration()
    {
        parent::setUp();
        Artisan::call('migrate');

        $this->visit('/register')
            ->type('name', 'name')
            ->type('email@stuff.com', 'email')
            ->type('123456', 'password')
            ->type('123456', 'password_confirmation')
            ->press('submit')
            ->seePageIs('/dashboard');

        $this->seeInDatabase('users', [
                'email' => 'email@stuff.com'
            ]);
    }

    /**
     * Log in system in classic way.
     *
     * @return void
     */
    public function testRegistrationSameUser()
    {
        parent::setUp();
        Artisan::call('migrate');

        $this->visit('/register')
            ->type('pepa', 'name')
            ->type('email@stuff.com', 'email')
            ->type('123456', 'password')
            ->type('123456', 'password_confirmation')
            ->press('submit')
            ->seePageIs('/register');

        $this->seeInDatabase('users', [
                'email' => 'email@stuff.com'
            ]);
    }

    /**
    * Log in system in classic way.
    *
    * @return void
    */
    public function testLogAndLogout()
    {
        $this->seeInDatabase('users', [
                'email' => 'email@stuff.com'
            ]);

        $this->visit('/')
            ->seePageIs('/login')
            ->type('email@stuff.com', 'email')
            ->type('123456', 'password')
            ->press('submit')
            ->seePageIs('/dashboard')
            ->press('logout')
            ->seePageIs('/login');

            Artisan::call('migrate:reset');
    }

}
