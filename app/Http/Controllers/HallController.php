<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use App\Http\Requests\StoreHallRequest;
use App\Http\Requests\UpdateHallRequest;
use App\Http\Resources\HallCollection;
use App\Http\Resources\HallResource;

class HallController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new HallCollection(Hall::all());
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
    public function store(StoreHallRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Hall $hall)
    {
        return new HallResource($hall);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hall $hall)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHallRequest $request, Hall $hall)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hall $hall)
    {
        //
    }
}
