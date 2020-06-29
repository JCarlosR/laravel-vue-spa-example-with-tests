<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, [
            User::ROLE_ADMIN,
            User::ROLE_USER_MANAGER
        ]);
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
        return $task->user_id === $user->id;
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
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Task $task
     * @return mixed
     */
    public function update(User $user, Task $task)
    {
        return $task->user_id === $user->id;
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
        return $task->user_id === $user->id;
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
        return $task->user_id === $user->id;
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
        return in_array($user->role, [
            User::ROLE_ADMIN,
            User::ROLE_USER_MANAGER
        ]);
    }
}
