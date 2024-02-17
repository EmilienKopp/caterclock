<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->ableTo('index', Project::class)
            ? Response::allow()
            : Response::deny('You are not authorized to browse projects.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): Response
    {
        return $user->ableTo('show', Project::class) && $user->ableTo('show', $project->company)
            ? Response::allow()
            : Response::deny('You are not authorized to view this project.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->ableTo('create', Project::class) 
            ? Response::allow()
            : Response::deny('You are not authorized to create projects.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): Response
    {
        return $user->ableTo('update', Project::class) && $user->owns($project)
            ? Response::allow()
            : Response::deny('You are not authorized to update this project.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): Response
    {
        return $user->ableTo('delete', Project::class) && $user->owns($project)
            ? Response::allow()
            : Response::deny('You are not authorized to delete this project.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): Response
    {
        return $user->ableTo('create', Project::class) && $user->owns($project)
            ? Response::allow()
            : Response::deny('You are not authorized to restore this project.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): Response
    {
        return $user->ableTo('delete', Project::class) && $user->owns($project)
            ? Response::allow()
            : Response::deny('You are not authorized to permanently delete this project.');
    }
}
