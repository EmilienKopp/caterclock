<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConnectionRequestRequest;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use App\Models\Position;
use App\Http\Requests\StorePositionRequest;
use App\Models\Company;
use App\Models\ConnectionRequest;
use App\Models\User;
use App\Models\Role;

class PositionController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(StorePositionRequest $request)
    {
        $validated = $request->validated();
        $user = User::find($request->user_id);

        $validatedForConnectionRequest = $request->validate([
            'sender_id' => 'required|integer|exists:users,id',
            'receiver_id' => 'required_without:company_id|integer|exists:users,id',
            'company_id' => 'required_without:receiver_id|integer|exists:companies,id',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        switch($validated["role_id"]) {
            case Role::id('employee'):
            case Role::id('freelancer'):
                ConnectionRequest::create($validatedForConnectionRequest);
                break;
            case Role::id('owner'):
            case Role::id('manager'):
                $user->companies()->attach($request->company_id, ['role_id' => $request->role_id]);
                break;
            // TO DO: Add the rest of the roles
        }


        return redirect()->back();
    }
}
