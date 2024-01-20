<?php

namespace App\Http\Controllers;

use App\Models\DiaryEntry;
use App\Http\Requests\StoreDiaryEntryRequest;
use App\Http\Requests\UpdateDiaryEntryRequest;

class DiaryEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entries = DiaryEntry::all();

        return inertia('DiaryEntry/Index', [
            'entries' => $entries,
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
    public function store(StoreDiaryEntryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DiaryEntry $diaryEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DiaryEntry $diaryEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiaryEntryRequest $request, DiaryEntry $diaryEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DiaryEntry $diaryEntry)
    {
        //
    }
}
