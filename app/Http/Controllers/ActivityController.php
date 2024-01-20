<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\DailyLog;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\TaskCategory;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::where('user_id', auth()->user()->id)
            ->where('date', Carbon::today())
            ->with('project')
            ->orderBy('id', 'asc')
            ->get()
            ->transform(function ($activity) {
                $activity->dailyLog = $activity->dailyLog();
                return $activity;
            });
        $projects = Project::all();
        $dailyLogs = DailyLog::where('user_id', auth()->user()->id)
            ->where('date', Carbon::today())
            ->get();
        $taskCategories = TaskCategory::all();
        return inertia('Activity/Index', compact('activities', 'projects', 'dailyLogs', 'taskCategories'));
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
    public function store(StoreActivityRequest $request)
    {
        $validated = $request->validated();
        Log::debug($validated);
        $activities = $validated['activities'];
        Log::debug($activities);
        Activity::where('user_id', auth()->user()->id)
            ->where('project_id', $activities[0]['project_id'])
            ->where('date', Carbon::today())
            ->delete();
        Activity::upsert($activities, ['project_id', 'user_id', 'task_category_id', 'date']);
        return to_route('activities.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        //
    }
}
