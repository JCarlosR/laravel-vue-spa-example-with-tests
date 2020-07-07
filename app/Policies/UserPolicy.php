<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    private function isAuthorizedRole($user)
    {
        return in_array($user->role, [
            User::ROLE_ADMIN,
            User::ROLE_USER_MANAGER
        ]);
    }
    
    /**
     * Determine if the user can list other users.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->isAuthorizedRole($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $this->isAuthorizedRole($user) || $user->id === $model->id;
    }

    /**
     * Determine if the user can create other users (besides the external register form).
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->isAuthorizedRole($user);
    }

    /**
     * Determine whether the user can update other users data.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        if ($model->role === User::ROLE_REGULAR_USER)
            return $this->isAuthorizedRole($user) || $user->id === $model->id;
        
        // special roles can only be updated by admins
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        if ($model->role === User::ROLE_REGULAR_USER)
            return $this->isAuthorizedRole($user) || $user->id === $model->id;

        // special roles can only be deleted by admins
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        if ($model->role === User::ROLE_REGULAR_USER)
            return $this->isAuthorizedRole($user) || $user->id === $model->id;

        // special roles can only be restored by admins
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        if ($model->role === User::ROLE_REGULAR_USER)
            return $this->isAuthorizedRole($user) || $user->id === $model->id;

        // special roles can only be deleted physically from db by admins
        return $user->role === User::ROLE_ADMIN;
    }
}
