<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RateController extends Controller
{


    public function upsert(Request $request, User $user, Project $project)
    {
        $request->validate([
            'rate' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'task_categories' => 'sometimes|array',
            'task_categories.*' => 'exists:task_categories,id',
        ]);

        $taskCategoryIds = collect($request->input('task_categories', []));

        DB::beginTransaction();

        $existingRates = Rate::where('user_id', $user->id)
            ->where('project_id', $project->id)
            ->when($taskCategoryIds, function ($query) use ($taskCategoryIds) {
                return $query->whereIn('task_category_id', $taskCategoryIds);
            })
            ->get();

        // Delete rates not in the new task_categories list, if provided
        if (!empty($taskCategoryIds)) {
            $existingRates->whereNotIn('task_category_id', $taskCategoryIds)->each->delete();
        }

        if($taskCategoryIds->isEmpty()){
            $ratesToUpsert = [[
                'user_id' => $user->id,
                'project_id' => $project->id,
                'rate' => $request['rate'],
                'currency' => $request['currency'],
            ]];
        }else{
            $ratesToUpsert = $taskCategoryIds->map(function ($id) use ($user, $project, $request) {
                return [
                    'user_id' => $user->id,
                    'project_id' => $project->id,
                    'task_category_id' => $id,
                    'rate' => $request['rate'],
                    'currency' => $request['currency'],
                ];
            })->all();
        }

        foreach ($ratesToUpsert as $rate) {
            Rate::updateOrCreate(
                [
                    'user_id' => $rate['user_id'],
                    'project_id' => $rate['project_id'],
                    'task_category_id' => $rate['task_category_id'] ?? null,
                ],
                [
                    'rate' => $rate['rate'],
                    'currency' => $rate['currency'],
                ]
            );
        }

        DB::commit();
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Rate $rate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rate $rate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rate $rate)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rate $rate)
    {
        //
    }
}
