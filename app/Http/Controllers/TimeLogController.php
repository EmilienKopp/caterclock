<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTimeLogRequest;
use App\Models\TimeLog;
use App\Http\Requests\StoreTimeLogRequest;
use App\Http\Requests\UpdateTimeLogRequest;
use App\Http\Requests\BatchUpdateTimeLogRequest;
use App\Models\DailyLog;
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

        $entries = TimeLog::todays($user->id);

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
        DB::transaction(function () use ($request) {

            $validated = $request->validated();
            $timezone = $validated['timezone'];
            // Both time fields are present: it's a manual entry
            if($request->has("in_time") && $request->has("out_time")
                && $request["in_time"] != null && $request["out_time"] != null )
            {
                $inTime = Carbon::parse($validated["in_time"],$timezone);
                $outTime = Carbon::parse($validated["out_time"],$timezone);
                $date = $inTime->format('Y-m-d');
                $validated["duration"] = $inTime->diffInMinutes($outTime);

                
               
                TimeLog::create([
                    'project_id' => $validated['project_id'],
                    'user_id' => $validated['user_id'],
                    'in_time' => $inTime->utc(),
                    'out_time' => $outTime->utc(),
                    'is_running' => false,
                    'date' => $date,
                    'timezone' => $timezone,
                    'total_duration' => $validated["duration"]
                ]);
                return to_route('activities.show', [
                    'date' => $date
                ]);
            }
            
            $latest = TimeLog::latest($validated['user_id']);
           
            if($latest && $latest->is_running) {
                $latest->out();
            }
            
            if(!$latest ||( $latest->project_id != $validated['project_id'] && !$latest->is_running)) {
                $latest = TimeLog::create([
                    'project_id' => $validated['project_id'],
                    'user_id' => $validated['user_id'],
                    'in_time' => Carbon::now(),
                    'is_running' => true,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'timezone' => $timezone,
                ]);

            }
            $latest->user->update(['timezone' => $timezone]);

        });

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
    public function update(UpdateTimeLogRequest $request, TimeLog $timelog)
    {
        try {
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
                    $inTime = Carbon::parse($entry['in_time'],$timelog->timezone);
                    $timelog->in_time = $inTime->utc();
                    $outTime = Carbon::parse($entry['out_time'],$timelog->timezone);
                    $timelog->out_time = $outTime->utc();
                    $timelog->save();
                }
            });

            return to_route('activities.show');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(status: 500, content: $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * Since more than one time log exists for a user in a day for a project,
     * all time logs for the day and project are deleted when a single time log is deleted.
     */
    public function destroy(TimeLog $timelog)
    {
        $timeLogs = TimeLog::where('project_id', $timelog->project_id)
            ->where('user_id', $timelog->user_id)
            ->whereDate('in_time', $timelog->in_time->format('Y-m-d'))
            ->get();
        
        $success = $timeLogs->each(function($timeLog) {
            $timeLog->delete();
        });

        if($success) {
            return response(status: 200);
        } else {
            return response(status: 500);
        }
    }
}
