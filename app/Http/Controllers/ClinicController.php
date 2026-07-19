<?php

namespace App\Http\Controllers;

use App\services\ClinicService;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function __construct(private ClinicService $clinicService) {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinics=$this->clinicService->getAll();
        return view('clinics.index',compact('clinics'));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
