<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/** @see RegisterController */
class RegisterControllerTest extends TestCase
{
    /** @test */
    public function testUserRegistrationAndEmailVerificationNotification()
    {
        Notification::fake(); // to test the VerifyEmail notification
        
        $testEmail = 'test@test.app';
        
        $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => $testEmail,
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ])
        ->assertSuccessful()
        ->assertJson([
            'status' => trans('verification.sent')
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $testEmail,
            'email_verified_at' => null
        ]);
        
        $user = User::where('email', $testEmail)->whereNull('email_verified_at')->first();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function testUserCanNotRegisterWithAnExistingEmail()
    {
        $testEmail = 'test@test.app';
        
        factory(User::class)->create([
            'email' => $testEmail
        ]);

        $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => $testEmail,
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
    }
}
