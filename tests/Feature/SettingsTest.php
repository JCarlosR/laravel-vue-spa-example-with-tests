<?php

namespace Tests\Feature;

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/** 
 * @see ProfileController
 * @see PasswordController 
 */
class SettingsTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function testProfileUpdate()
    {
        $this->actingAs($this->user)
            ->patchJson('/api/settings/profile', [
                'name' => 'Test User',
                'email' => 'test@test.app',
                'working_hours' => 3
            ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'id', 'name', 'email', 'working_hours'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Test User',
            'email' => 'test@test.app',
            'working_hours' => 3
        ]);
    }

    /** @test */
    public function testPasswordUpdate()
    {
        $newPassword = 'updated';
        
        $this->actingAs($this->user)
            ->patchJson('/api/settings/password', [
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ])
            ->assertSuccessful();

        $this->assertTrue(Hash::check($newPassword, $this->user->password));
    }
}
