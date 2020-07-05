<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Tests\TestCase;

/** @see LoginController */
class LoginControllerTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function testUserAuthentication()
    {
        $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ])
        ->assertSuccessful()
        ->assertJsonStructure([
            'token', 'token_type', 'expires_in'
        ])
        ->assertJson([
            'token_type' => 'bearer'
        ]);
    }

    /** @test */
    public function testGetAuthenticatedUser()
    {
        $this->actingAs($this->user)
            ->getJson('/api/user')
            ->assertSuccessful()
            ->assertJsonStructure([
                'id', 'name', 'email', 'role',
                'working_hours' // this is null by default, but the Factory sets a value
            ]);
    }

    /** @test */
    public function testLogoutInvalidatesToken()
    {
        $token = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ])->json()['token'];

        $this->postJson("/api/logout?token=$token")
            ->assertSuccessful();

        $this->getJson("/api/user?token=$token")
            ->assertUnauthorized(); // same as ->assertStatus(401);
    }
}
