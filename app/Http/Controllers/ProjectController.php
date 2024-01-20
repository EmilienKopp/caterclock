<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::getOwnedByAuthUser();
        return inertia('Project/Index', compact('projects'));
    }

    public function create()
    {
        return inertia('Project/Create');
    }

    public function store(StoreProjectRequest $request)
    {
        Project::create($request->validated());
        return to_route('projects.index');
    }

}
