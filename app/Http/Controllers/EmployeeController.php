<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function index()
    {
        // All companies with their employees
        $user = User::find(Auth::id());
        $companies = $user->ownedCompanies()
            ->with(['connectionRequests', 'employees', 'hiredFreelancers', 'admins'])
            ->get();

        return Inertia::render('Employees/Index', [
            'companies' => $companies,
        ]);
    }
}
