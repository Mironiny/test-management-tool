<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class loginTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Redirect to login page if you are not autenticated.
     *
     * @return void
     */
    public function testRedirectToLoginPage()
    {
        $this->visit('/')
            ->seePageIs('/login');
    }

    /**
     * Log in system in classic way.
     *
     * @return void
     */
    public function testLogInSystem1()
    {
            $user = factory(App\User::class)->create();

            $this->seeInDatabase('users', [
                'email' => $user->email
            ]);

            $this->seeInDatabase('users', [
                'password' => $user->password
            ]);

            $response = $this->call('POST', url('/login'), ['email' => $user->email, 'password' => $user->password]);

    }

    /**
     * Log in system in classic way.
     *
     * @return void
     */
    public function testLogInSystem()
    {
        $user = factory(App\User::class)->create();

        $this->actingAs($user)
            ->visit('/')
            ->seePageIs('/dashboard');

    }






}
