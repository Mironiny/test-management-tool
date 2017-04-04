<?php
use Illuminate\Support\Facades\Artisan;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class registrationAndLog extends TestCase
{
    /**
     * Redirect to login page if you are not autenticated.
     *
     * @return void
     */
    public function testRedirectToLoginPage()
    {
        $this->visit('/')
            ->seePageIs('/login')
            ->visit('/dashboard')
            ->seePageIs('/login');
    }

    /**
     * Register in system in classic way.
     *
     * @return void
     */
    public function testRegistration()
    {
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
     * Register in system with duplicated email.
     *
     * @return void
     */
    public function testRegistrationSameUser()
    {

        $this->visit('/register')
            ->seePageIs('/register')
            ->type('pepa', 'name')
            ->type('email@stuff.com', 'email')
            ->type('123456', 'password')
            ->type('123456', 'password_confirmation')
            ->press('submit')
            ->seePageIs('/register');

        $this->dontSeeInDatabase('users', [
                'name' => 'pepa'
            ]);
    }

    /**
    * Test log in system and logout.
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
            ->assertResponseOk()
            ->seePageIs('/login');

            Artisan::call('migrate:reset');
    }

}
