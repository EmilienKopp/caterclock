<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProjectController extends Controller
{
    public function index()
    {
        $user = User::authenticated();
        if(!$user->can('viewAny', Project::class)) {
            return inertia('Error', ['message' => 'You are not authorized to browse projects.']);
        }
        $projects = $user->getInvolvedProjects();
        return inertia('Project/Index', compact('projects'));
    }

    public function show(Project $project)
    {
        $project = Project::where('id',$project->id)->with(['timeLogs','company'])->first();
        return inertia('Project/Show', compact('project'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);
        $user = User::authenticated();
        return inertia('Project/Create');
    }

    public function store(StoreProjectRequest $request)
    {
        Project::create($request->validated());
        return to_route('projects.index');
    }

    public function edit(Project $project)
    {
        return inertia('Project/Edit', compact('project'));
    }

    public function update(StoreProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return to_route('projects.index');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return to_route('projects.index');
    }
}
