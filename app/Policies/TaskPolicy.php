<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    private function isAuthorizedRole($user)
    {
        return in_array($user->role, [
            User::ROLE_ADMIN
        ]);
    }
    
    /**
     * Every user can list tasks, as long as they are the owners.
     *
     * @return mixed
     */
    public function viewAny()
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Task  $task
     * @return mixed
     */
    public function view(User $user, Task $task)
    {
        return $task->user_id === $user->id 
            || $this->isAuthorizedRole($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function create()
    {
        return true;
    }

    /**
     * Determine whether the user can update the task.
     *
     * @param User $user
     * @param Task $task
     * @return mixed
     */
    public function update(User $user, Task $task)
    {
        return $task->user_id === $user->id
            || $this->isAuthorizedRole($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Task $task
     * @return mixed
     */
    public function delete(User $user, Task $task)
    {
        return $task->user_id === $user->id
            || $this->isAuthorizedRole($user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Task $task
     * @return mixed
     */
    public function restore(User $user, Task $task)
    {
        return $task->user_id === $user->id
            || $this->isAuthorizedRole($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Task $task
     * @return mixed
     */
    public function forceDelete(User $user, Task $task)
    {
        return $task->user_id === $user->id
            || $this->isAuthorizedRole($user);
    }
}
