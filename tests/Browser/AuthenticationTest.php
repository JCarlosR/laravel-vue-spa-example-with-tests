<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\Browser\Pages\Login;
use Tests\Browser\Pages\TasksToday;
use Tests\DuskTestCase;
use Throwable;

class AuthenticationTest extends DuskTestCase
{
    public function setUp(): void
    {
        parent::setup();

        static::closeAll();
    }

    /** 
     * Check if the login form redirects to the list of today's tasks for the user.
     * 
     * @throws Throwable
     */
    public function testLoginWithValidCredentials()
    {
        $user = factory(User::class)->create();

        $this->browse(function ($browser) use ($user) {
            $browser->visit(new Login)
                ->submit($user->email, 'password')
                ->on(new TasksToday)
                ->waitFor('.menu-card', 1)
                ->assertSee('My Tasks');
        });
    }

    /**
     * Check if the login form really validates the user credentials.
     *
     * @throws Throwable
     */
    public function testLoginWithInvalidCredentials()
    {
        $this->browse(function ($browser) {
            $browser->visit(new Login)
                ->submit('test@test.app', 'password')
                ->assertSee('These credentials do not match our records.');
        });
    }

    /**
     * Check if the logout redirects to the login form.
     *
     * @throws Throwable
     */
    public function testLogout()
    {
        $user = factory(User::class)->create();

        $this->browse(function ($browser) use ($user) {
            $browser->visit(new Login)
                ->submit($user->email, 'password')
                ->on(new TasksToday)
                ->clickLogout()
                ->on(new Login);
        });
    }
}
