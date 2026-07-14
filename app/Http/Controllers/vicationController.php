<?php

namespace App\Http\Controllers;

use App\services\DoctorService;
use Illuminate\Http\Request;
use App\services\vicationService;
use Illuminate\Support\Facades\Auth;

class vicationController extends Controller
{
    public function __construct(private vicationService $vicationService, private DoctorService $doctorService) {}
    public function index()
    {
        $vications = $this->vicationService->getClinicVacations(Auth::user()->clinic_id);
        $doctors = $this->doctorService->getDoctorsNames(Auth::user()->clinic_id);
        return view('vacations.index', compact('vications','doctors'));
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
       return redirect()->route('home');
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
             return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
