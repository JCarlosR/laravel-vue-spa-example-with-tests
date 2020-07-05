<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\VerificationController;
use App\Notifications\VerifyEmail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/** @see VerificationController */
class VerificationControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        
        // otherwise the email verification will reach the limit and the tests will fail
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
    }
    
    /** @test */
    public function testUserCanVerifyEmail()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null
        ]);
        
        // this generates a verification link
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), ['user' => $user->id]);

        Event::fake();

        $this->postJson($url)
            ->assertSuccessful()
            ->assertJsonFragment([
                'status' => trans('verification.verified')
            ]);

        Event::assertDispatched(Verified::class, function (Verified $e) use ($user) {
            return $e->user->is($user);
        });
    }

    /** @test */
    public function testUserCanNotVerifyIfAlreadyVerified()
    {
        $user = factory(User::class)->create();

        // this generates a verification link
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), ['user' => $user->id]);

        $this->postJson($url)
            ->assertStatus(400)
            ->assertJsonFragment([
                'status' => trans('verification.already_verified')          
            ]);
    }

    /** @test */
    public function testInvalidVerificationIfUrlHasInvalidSignature()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);

        $this->postJson("/api/email/verify/{$user->id}")
            ->assertStatus(400)
            ->assertJsonFragment([
                'status' => trans('verification.invalid')
            ]);
    }

    /** @test */
    public function testResendVerificationNotification()
    {
        $user = factory(User::class)->create(['email_verified_at' => null]);

        Notification::fake();

        $this->postJson('/api/email/resend', ['email' => $user->email])
            ->assertSuccessful();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function testResendVerificationNotificationIsNotTriggeredIfEmailDoesNotExist()
    {
        $this->postJson('/api/email/resend', ['email' => 'foo@bar.com'])
            ->assertStatus(422)
            ->assertJsonFragment([
                'errors' => [
                    'email' => [trans('verification.user')]
                ]
            ]);
    }

    /** @test */
    public function testResendVerificationNotificationNotAvailableIfEmailIsAlreadyVerified()
    {
        $user = factory(User::class)->create();

        Notification::fake();

        $this->postJson('/api/email/resend', ['email' => $user->email])
            ->assertStatus(422)
            ->assertJsonFragment([
                'errors' => [
                    'email' => [trans('verification.already_verified')],
                ]
            ]);

        Notification::assertNotSentTo($user, VerifyEmail::class);
    }
}
