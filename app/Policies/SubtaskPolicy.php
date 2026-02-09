<?php

namespace App\Policies;

use App\Models\Subtask;
use App\Models\User;

class SubtaskPolicy
{
    /**
     * Determine whether the user can view any models.
     * Admin, Head, and Staff can view subtasks
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'head', 'staff']);
    }

    /**
     * Determine whether the user can view the model.
     * Admin can view all, Head can view their own, Staff can view assigned to them
     */
    public function view(User $user, Subtask $subtask): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isHead()) {
            return $subtask->created_by === $user->id;
        }

        if ($user->isStaff()) {
            return $subtask->assigned_to === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     * Admin and Head can create subtasks
     */
    public function create(User $user): bool
    {
        return $user->isHead();
    }

    /**
     * Determine whether the user can update the model.
     * Admin can update all, Head can only update their own, Staff can update assigned to them
     */
    public function update(User $user, Subtask $subtask): bool
    {
        if ($user->isHead()) {
            return $subtask->created_by === $user->id;
        }

        if ($user->isStaff()) {
            return $subtask->assigned_to === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * Admin can delete all, Head can only delete their own
     */
    public function delete(User $user, Subtask $subtask): bool
    {
        if ($user->isHead()) {
            return $subtask->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Subtask $subtask): bool
    {
        return $this->delete($user, $subtask);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Subtask $subtask): bool
    {
        return $this->delete($user, $subtask);
    }
}
