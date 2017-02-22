<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class frontendLogIn extends TestCase
{

    use DatabaseTransactions;
    /**
     * Log in system in classic way.
     *
     * @return void
     */
    public function testLogInSystem11()
    {
        $user = factory(App\User::class)->create();

        $this->seeInDatabase('users', [
            'email' => $user->email,
            'password' => $user->password
        ]);

        $this->visit('/login')
             ->type($user->email, 'email')
             ->type($user->password, 'password')
             ->press('submit')
             ->seePageIs('/dashboard');

    }
}
