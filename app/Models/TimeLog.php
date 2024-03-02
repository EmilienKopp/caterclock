<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeLog extends Model
{
    use HasFactory;
    protected $table = 'time_logs';
    protected $fillable = [
        'project_id',
        'user_id',
        'in_time',
        'out_time',
        'is_running',
        'total_duration',
        'date',
        'timezone'
    ];
    protected $casts = [
        'in_time' => 'datetime',
        'out_time' => 'datetime',
        'is_running' => 'boolean',
        'date' => 'date',
    ];

    public static function getForProjectAndUser($projectId, $userId)
    {
        return static::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->get();
    }

    public static function todays($userId) {
        return static::where('user_id', $userId)
            ->where('date', Carbon::today())
            ->with('project')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function latest($userId) {
        return static::where('user_id', $userId)
            ->where('date', Carbon::today())
            ->latest()
            ->first();
    }

    public static function latestRunning($userId) {
        return static::where('user_id', $userId)
            ->where('date', Carbon::today())
            ->where('out_time', null)
            ->latest()
            ->first();
    }

    public static function clockIn($validated)
    {
        $clockEntry = TimeLog::create([
            'project_id' => $validated['project_id'],
            'user_id' => $validated['user_id'],
            'in_time' => $validated['in_time'],
            'is_running' => true,
            'date' => Carbon::parse($validated['in_time'])->format('Y-m-d'),
        ]);

        return $clockEntry;
    }

    public static function clockOut($validated)
    {

        $clockEntry = TimeLog::where('user_id', $validated['user_id'])
            ->where('out_time', null)
            ->where('date', Carbon::today())
            ->with('project')
            ->first();

        $clockEntry->update([
            'out_time' => $validated['out_time'],
            'is_running' => false,
            'total_duration' => $clockEntry->in_time->diffInSeconds($validated['out_time']),
        ]);

        return $clockEntry;
    }

    public function in()
    {
        return $this->update([
            'in_time' => Carbon::now(),
            'is_running' => true,
        ]);
    }

    public function out() {
        return $this->update([
            'out_time' => Carbon::now(),
            'is_running' => false,
            'total_duration' => $this->in_time->diffInSeconds(Carbon::now()),
        ]);
    }

    public function getDuration() {
        if($this->is_running) {
            return $this->in_time->diffInSeconds(Carbon::now());
        } else {
            return $this->total_duration;
        }
    }

    /**-------------------------*/
    /** Relationships           */
    /**-------------------------*/
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Unknown User',
        ]);
    }
}
