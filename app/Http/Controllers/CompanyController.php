<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();
        $clients = Auth::user()->contractedCompanies;
        $employers = Auth::user()->employingCompanies;
        $ownedCompanies = Auth::user()->ownedCompanies;
        $managedCompanies = Auth::user()->managedCompanies;
        $sentConnectionRequests = Auth::user()->sentConnectionRequests;

        Log::debug($sentConnectionRequests);
        

        return Inertia::render('Companies/Index', [
            'companies' => $companies,
            'clients' => $clients,
            'employers' => $employers,
            'ownedCompanies' => $ownedCompanies,
            'managedCompanies' => $managedCompanies,
            'sentConnectionRequests' => $sentConnectionRequests,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $company->update($request->all());
        return response(status: 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
