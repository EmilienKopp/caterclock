<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

class ClockEntry extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'action',
        'user_id',
        'project_id',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
