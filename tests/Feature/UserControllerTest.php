<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

/** @see UserController */
class UserControllerTest extends TestCase
{
    /** @var User */
    protected $userRegular;
    protected $userManager;
    protected $userAdmin;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRegular = factory(User::class)->create([
            'role' => User::ROLE_REGULAR_USER
        ]);
        
        $this->userManager = factory(User::class)->create([
            'role' => User::ROLE_USER_MANAGER
        ]);
        
        $this->userAdmin = factory(User::class)->create([
            'role' => User::ROLE_ADMIN
        ]);
    }

    /** @test */
    public function testRegularUserCanNotModifyOtherUsersData()
    {
        $otherRegularUser = factory(User::class)->create();

        $originalName = $otherRegularUser->name;
        
        $this->actingAs($this->userRegular)
            ->putJson('/api/users/'.$otherRegularUser->id, [
                'name' => 'Updated name'
            ])
            ->assertForbidden();

        // the name of the other regular user remains the same
        $this->assertDatabaseHas('users', [
            'id' => $otherRegularUser->id,
            'name' => $originalName
        ]);
            
    }
    
    /** @test */
    public function testUserManagerCanNotModifyAdminData()
    {        
        $originalName = $this->userAdmin->name;
        
        // $response = 
        $this
            ->actingAs($this->userManager)
            ->putJson('/api/users/'.$this->userAdmin->id, [
                'name' => 'Updated name',
                'email' => 'updated@email.com'
            ])
            ->assertForbidden();        
        // dd($response->decodeResponseJson());

        // the name of the admin user remains the same
        $this->assertDatabaseHas('users', [
            'id' => $this->userAdmin->id,
            'name' => $originalName
        ]);
    }

    /** @test */
    public function testUserAdminCanEditRegularUserData()
    {
        $newName = 'User name modified by an admin';
        $newEmail = 'updated@email.com';

        $this->actingAs($this->userAdmin)
            ->putJson('/api/users/'.$this->userRegular->id, [
                'name' => $newName,
                'email' => $newEmail
            ])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $this->userRegular->id,
            'name' => $newName,
            'email' => $newEmail
        ]);
    }

    /** @test */
    public function testUserAdminCanEditManagerUserData()
    {
        $newName = 'User name modified by an admin';
        $newEmail = 'updated@email.com';

        $this->actingAs($this->userAdmin)
            ->putJson('/api/users/'.$this->userManager->id, [
                'name' => $newName,
                'email' => $newEmail
            ])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $this->userManager->id,
            'name' => $newName,
            'email' => $newEmail
        ]);
    }

}
