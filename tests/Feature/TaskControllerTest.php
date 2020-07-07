<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

/** @see TaskController */
class TaskControllerTest extends TestCase
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
    public function testEveryUserRoleCanRegisterTasks()
    {
        $this->assertTaskRegistrationFor($this->userRegular);
        $this->assertTaskRegistrationFor($this->userManager);
        $this->assertTaskRegistrationFor($this->userAdmin);
    }
    
    /** @test */
    public function testRegularUserCanNotEditTasksFromOthers()
    {
        $otherRegularUser = factory(User::class)->create();
        
        $task = factory(Task::class)->create([
            'user_id' => $otherRegularUser->id
        ]);
        
        $originalTitle = $task->title;
        
        $taskData = [
            'title' => 'Updated title'
        ];
        
        $this->actingAs($this->userRegular)
            ->putJson('/api/tasks/'.$task->id, $taskData)
            ->assertForbidden();
        
        // title remains the same
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $originalTitle
        ]);
    }

    /** @test */
    public function testUserAdminCanEditTasksFromAnyone()
    {
        $userManagerTask = factory(Task::class)->create([
            'user_id' => $this->userManager->id
        ]);

        $userRegularTask = factory(Task::class)->create([
            'user_id' => $this->userRegular->id
        ]);
        
        $newTitle = 'Title updated by an admin';

        $taskData = [
            'title' => $newTitle
        ];

        $this->actingAs($this->userAdmin)
            ->putJson('/api/tasks/'.$userManagerTask->id, $taskData)
            ->assertSuccessful();

        $this->actingAs($this->userAdmin)
            ->putJson('/api/tasks/'.$userRegularTask->id, $taskData)
            ->assertSuccessful();

        // title updated for both tasks
        $this->assertDatabaseHas('tasks', [
            'id' => $userManagerTask->id,
            'title' => $newTitle
        ]);
        $this->assertDatabaseHas('tasks', [
            'id' => $userRegularTask->id,
            'title' => $newTitle
        ]);
    }

    private function assertTaskRegistrationFor(User $user)
    {
        $taskData = [
            'title' => 'Task created by a regular user',
            'description' => 'Task created by a regular user',
            'duration' => 10
        ];

        $this
            ->actingAs($user)
            ->postJson('/api/tasks', $taskData)
            ->assertSuccessful()
            ->assertJsonStructure([
                'title', 'description', 'duration', 'id'
            ])
            ->assertJson($taskData);
    }
}
