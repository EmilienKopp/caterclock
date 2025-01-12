<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Activity extends Model
{
    use HasFactory;
    public $fillable = [
        'project_id',
        'user_id',
        'task_category_id',
        'task_id',
        'comment',
        'start_time',
        'end_time',
        'duration',
        'hours',
        'minutes',
        'rate',
    ];
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function getByYearMonth($year, $month)
    {
        return Activity::where('user_id', auth()->user()->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('project')
            ->with('taskCategory')
            ->orderBy('date', 'asc')
            ->get()
            ->transform(function ($activity) {
                $activity->dailyLog = $activity->dailyLog();
                return $activity;
            });
    }

    public function dailyLog()
    {
        return DailyLog::where('user_id', $this->user_id)
            ->where('project_id', $this->project_id)
            ->where('date', $this->date)
            ->first();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function taskCategory()
    {
        return $this->belongsTo(TaskCategory::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
