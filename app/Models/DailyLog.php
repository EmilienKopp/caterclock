<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    use HasFactory;
    protected $table = 'daily_project_durations';

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
