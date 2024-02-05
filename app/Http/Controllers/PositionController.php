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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

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


        if($validated["position"] == 'employee') {
            $validatedForConnectionRequest = $request->validate([
                'sender_id' => 'required|integer|exists:users,id',
                'receiver_id' => 'required_without:company_id|integer|exists:users,id',
                'company_id' => 'required_without:receiver_id|integer|exists:companies,id',
                'position' => 'required|string|in:owner,employee,hired_freelance,admin',
            ]);
            $connectionRequest = ConnectionRequest::create($validatedForConnectionRequest);
        } else {
            $user->companies()->attach($request->company_id, ['position' => $request->position]);
        }


        return redirect()->back();
    }
}
