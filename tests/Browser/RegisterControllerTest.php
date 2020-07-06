<?php

namespace Tests\Browser;

use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Tests\Browser\Pages\Login;
use Tests\Browser\Pages\Register;
use Tests\Browser\Pages\TasksToday;
use Tests\DuskTestCase;
use Throwable;

/** @see RegisterController */
class RegisterControllerTest extends DuskTestCase
{
    public function setUp(): void
    {
        parent::setup();

        static::closeAll();
    }

    /**
     * Verify the register form redirects to the list of tasks after a complete registration.
     *
     * @throws Throwable
     */
    public function testRegisterWithValidData()
    {
        $this->browse(function ($browser) {
            $browser->visit(new Register)
                ->submit([
                    'name' => 'Test User',
                    'email' => 'test@test.app',
                    'password' => 'password',
                    'password_confirmation' => 'password',
                ])
                ->waitFor('@alert-success', 1)
                ->assertSee(trans('verification.sent'));
        });
    }


    /**
     * Verify the register does not proceed with emails that are already taken by registered users.
     *
     * @throws Throwable
     */
    public function testRegisterValidatesUniqueEmails()
    {
        $user = factory(User::class)->create();

        $this->browse(function ($browser) use ($user) {
            $browser->visit(new Register)
                ->submit([
                    'name' => 'Test User',
                    'email' => $user->email,
                    'password' => 'password',
                    'password_confirmation' => 'password',
                ])
                ->assertSee('The email has already been taken.');
        });
    }
    
}
