<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     * Admin, Supervisor, Head can view projects list
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'supervisor', 'head']);
    }

    /**
     * Determine whether the user can view the model.
     * Admin, Supervisor, Head can view project details
     */
    public function view(User $user, Project $project): bool
    {
        return in_array($user->role, ['admin', 'supervisor', 'head']);
    }

    /**
     * Determine whether the user can create models.
     * Only Admin can create projects
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     * Only Admin can manage/update projects
     */
    public function update(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     * Only Admin can delete projects
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if user can manage project (custom method)
     * Only admin can manage, head cannot despite seeing the project
     */
    public function manage(User $user, Project $project): bool
    {
        return $user->isAdmin();
    }
}
