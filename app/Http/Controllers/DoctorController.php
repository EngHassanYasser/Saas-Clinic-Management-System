<?php

namespace App\Http\Controllers;

use App\Http\Requests\doctor\StoreDoctorRequest;
use App\services\DoctorService;
use App\services\SpecialityService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private DoctorService $doctorService, private SpecialityService $specialityService) {}
    public function index()
    {
        $doctors = $this->doctorService->getAll();
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specialities = $this->specialityService->getAll();
        return view('doctors.create', compact('specialities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorRequest $request, DoctorService $doctorService)
    {
        $newDoctor =  $doctorService->addNew($request->validated());
        return $newDoctor;
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
        $isDeleted = $this->doctorService->deleteById($id);
        return response()->json([
            'success' => $isDeleted > 0,
            'message' => $isDeleted > 0
                ? 'doctor deleted successfully'
                : 'doctor not found'
        ]);
    }
}
