<?php

namespace App\Http\Controllers;

use App\Models\ConnectionRequest;
use App\Http\Requests\StoreConnectionRequestRequest;
use App\Http\Requests\UpdateConnectionRequestRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Role;
use Illuminate\Http\Request;

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

        $employeeRole = Role::where('name', 'employee')->first();
        $validated = $request->validated();
        $validated['status'] = 'accepted';
        $connectionRequest->update($validated);
        $connectionRequest->sender->companies()->attach($connectionRequest->company, ['role_id' => $employeeRole->id]);
    }

    public function decline(UpdateConnectionRequestRequest $request, ConnectionRequest $connectionRequest){
        $validated = $request->validated();
        $validated['status'] = 'declined';
        $connectionRequest->update($validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConnectionRequestRequest $request)
    {

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
        // ...

        return redirect()->route('employees.index', compact('companies'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConnectionRequest $connectionRequest)
    {
        $this->authorize('delete', $connectionRequest);
        $success = false;
        if($connectionRequest->status == 'pending'){
            $success = $connectionRequest->delete();
        }
        
        if($success){
            return redirect()->route('employees.index');
        } else {
            return redirect()->back()->with('error', 'Connection request could not be deleted.');
        }
    }
}
