<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyLog extends Model
{
    use HasFactory;
    protected $table = 'daily_logs';

    public static function getMonthly($date)
    {
        return static::where('user_id', auth()->user()->id)
            ->whereYear('date', Carbon::parse($date)->year)
            ->whereMonth('date', Carbon::parse($date)->month)
            ->orderBy('date', 'desc')
            ->get()
            ->transform(function ($dailyLog) {
                $dailyLog->activities = $dailyLog->activities();
                $dailyLog->timeLogs = $dailyLog->timeLogs();
                if(!$dailyLog->total_seconds){
                    $dailyLog->total_seconds = Carbon::parse($dailyLog->end_time)->diffInSeconds(Carbon::parse($dailyLog->start_time));
                }
                return $dailyLog;
            })
            ->groupBy('date');
    }

    public static function getDaily($date)
    {
        return static::where('user_id', auth()->user()->id)
            ->where('date', $date)
            ->orderBy('date', 'desc')
            ->get()
            ->transform(function ($dailyLog) {
                $dailyLog->activities = $dailyLog->activities();
                $dailyLog->timeLogs = $dailyLog->timeLogs();
                if(!$dailyLog->total_seconds){
                    $dailyLog->total_seconds = Carbon::parse($dailyLog->end_time)->diffInSeconds(Carbon::parse($dailyLog->start_time));
                }
                return $dailyLog;
            });
    }

    private static function joinActivitiesAndTimeLogs($dailyLog)
    {
        $dailyLog->activities = $dailyLog->activities();
        $dailyLog->timeLogs = $dailyLog->timeLogs();
        if(!$dailyLog->total_seconds){
            $dailyLog->total_seconds = Carbon::parse($dailyLog->end_time)->diffInSeconds(Carbon::parse($dailyLog->start_time));
        }
        return $dailyLog;
    }

    public function timeLogs()
    {
        return TimeLog::where('user_id', $this->user_id)
            ->where('project_id', $this->project_id)
            ->where('date', $this->date)
            ->with('project')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function activities()
    {
        return Activity::where('user_id', $this->user_id)
            ->where('project_id', $this->project_id)
            ->where('date', $this->date)
            ->with('project', 'taskCategory')
            ->orderBy('id', 'asc')
            ->get();
    }
}
