<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTimeLogRequest;
use App\Models\TimeLog;
use App\Http\Requests\StoreTimeLogRequest;
use App\Http\Requests\UpdateTimeLogRequest;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\TimeCalculation;

class TimeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $userId = auth()->user()->id;
        $entries = TimeLog::where('user_id', $userId)
                    ->where('date', Carbon::today())
                    ->with('project')
                    ->orderBy('created_at', 'desc')
                    ->get();

        $projects = Project::all();

        $calculator = new TimeCalculation($entries);

        $projectDurations = $calculator->perProject();

        Log::debug($projectDurations);

        return inertia('TimeLog/Index', compact('entries', 'projects', 'projectDurations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTimeLogRequest $request)
    {
        $validated = $request->validated();
        
        $clockEntry = TimeLog::latestRunning(auth()->user()->id);

        // if out time is before 4am, set "today" to yesterday
        // if(Carbon::now()->hour < 4) {
        //     $validated['date'] = Carbon::yesterday();
        // } else {
        //     $validated['date'] = Carbon::today();
        // }

        if(!$clockEntry) { // if there is no "running" clock entry, create one
            $clockEntry = TimeLog::clockIn($validated);
        } else { // if there is a clock entry, stop it
            $clockEntry->out();

            if($validated["project_id"] != $clockEntry->project_id) { // if the selected project is different, also create a new entry
                $clockEntry = TimeLog::clockIn($validated);
            }
        }

        return to_route('timelog.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(TimeLog $timeLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimeLog $timeLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTimeLogRequest $request, TimeLog $timeLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeLog $timeLog)
    {
        //
    }
}
