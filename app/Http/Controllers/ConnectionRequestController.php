<?php

namespace App\Http\Controllers;

use App\Models\ConnectionRequest;
use App\Http\Requests\StoreConnectionRequestRequest;
use App\Http\Requests\UpdateConnectionRequestRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ConnectionRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function accept(UpdateConnectionRequestRequest $request, ConnectionRequest $connectionRequest){
        $validated = $request->validated();
        $connectionRequest->update($validated);
        $connectionRequest->sender->companies()->attach($connectionRequest->company, ['position' => 'employee']);
        $connectionRequest->company->users()->attach($connectionRequest->sender, ['position' => 'employee']);
        $connectionRequest->delete();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConnectionRequestRequest $request)
    {
        $validated = $request->validated();
    }

    /**
     * Display the specified resource.
     */
    public function show(ConnectionRequest $connectionRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConnectionRequest $connectionRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConnectionRequestRequest $request, ConnectionRequest $connectionRequest)
    {
        Log::debug($request);
        $validated = $request->validated();
        $user = User::find(Auth::id());
        Log::debug($validated);
        if($validated['status'] === 'accepted'){
            $company = $user->companies()->where('id', $validated['company_id'])->first();
            $connectionRequest->sender->companies()->attach($company, ['position' => 'employee']);
            $connectionRequest->delete();
        }
        else {
            $connectionRequest->update($validated);
        }

        $companies = $user->ownedCompanies()
            ->withPivot('position')
            ->with('connectionRequests')
            ->get();

        return redirect()->route('employees.index', compact('companies'));
    }

    /**W
     * Remove the specified resource from storage.
     */
    public function destroy(ConnectionRequest $connectionRequest)
    {
        //
    }
}
