<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTimeLogRequest;
use App\Models\TimeLog;
use App\Http\Requests\StoreTimeLogRequest;
use App\Http\Requests\UpdateTimeLogRequest;
use App\Http\Requests\BatchUpdateTimeLogRequest;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\TimeCalculation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TimeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::authenticated();
        $entries = TimeLog::where('user_id', $user->id)
                    ->where('date', Carbon::today())
                    ->with('project')
                    ->orderBy('created_at', 'desc')
                    ->get();

        $projects = $user->getInvolvedProjects();

        $calculator = new TimeCalculation($entries);
        $projectDurations = $calculator->perProject();

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

        if(!$clockEntry) { // if there is no "running" clock entry, create one
            $clockEntry = TimeLog::clockIn($validated);
        } else { // if there is a clock entry, stop it
            $clockEntry->out();

            if($validated["project_id"] != $clockEntry->project_id) { // if the selected project is different, also create a new entry
                $clockEntry = TimeLog::clockIn($validated);
            }
        }

        return redirect()->back();
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
    public function update(UpdateTimeLogRequest $request, TimeLog $timelog)
    {
        try {
            $timelog = TimeLog::find($request->id);
            $timelog->out();
            return response(status: 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(status: 500, content: $e->getMessage());
        }
    }

    public function batchUpdate(BatchUpdateTimeLogRequest $request)
    {
        
        try {
            $validated = $request->validated();
            DB::transaction(function () use ($validated) {
                foreach ($validated['entries'] as $entry) {
                    $timelog = TimeLog::find($entry['id']);
                    $timelog->in_time = $entry['in_time'];
                    $timelog->out_time = $entry['out_time'];
                    $timelog->save();
                }
            });

            return redirect()->back();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(status: 500, content: $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeLog $timeLog)
    {
        //
    }
}
