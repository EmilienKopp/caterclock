<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeLog;
use App\Models\User;
use App\Services\TimeCalculation;

class DashboardController extends Controller
{
    public function index() {
        $user = User::authenticated();

        $entries = TimeLog::todays($user->id);

        $projects = $user->getInvolvedProjects();

        $calculator = new TimeCalculation($entries);
        
        $projectDurations = $calculator->perProject();

        return inertia('Dashboard', compact('entries', 'projects', 'projectDurations'));
    }
}
