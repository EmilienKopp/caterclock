<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class TimeCalculation {

    private Collection $logs;

    public function __construct(Collection $logs)
    {
        $this->logs = $logs;
    }

    public function calculateTotalDuration($projectId = null): int
    {
        if($projectId) {
            return $this->logs->where('project_id', $projectId)->sum('total_duration');
        }
        return $this->logs->sum('total_duration');
    }

    public static function secondsToTimeStrings(int $seconds): array
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;

        return compact('seconds','minutes','hours');
    }

    public function perProject(): Collection {
        $projects = $this->logs->groupBy('project_id');
        $projects->transform(function($project) {
            $total_seconds = $project->sum('total_duration');
            $times = static::secondsToTimeStrings($total_seconds);
            return [
                'total_seconds' => $total_seconds,
                'hours' => $times['hours'],
                'minutes' => $times['minutes'],
                'seconds' => $times['seconds'],
            ];
        });
        return $projects;
    }

}