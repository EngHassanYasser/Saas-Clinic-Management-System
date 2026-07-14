<?php

namespace App\Http\Controllers;

use App\Http\Requests\vications\StoreVicationsRequest;
use App\Http\Requests\vications\UpdateVicationsRequest;
use App\services\DoctorService;
use App\services\vicationService;
use Illuminate\Support\Facades\Auth;

class vicationController extends Controller
{
    public function __construct(private vicationService $vicationService, private DoctorService $doctorService) {}
    public function index()
    {
        $vications = $this->vicationService->getClinicVacations(Auth::user()->clinic_id);
        $doctors = $this->doctorService->getDoctorsNames(Auth::user()->clinic_id);
        $stats = $this->vicationService->getStatistics();
        
        return view('vacations.index', compact('vications', 'doctors','stats'));
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
    public function store(StoreVicationsRequest $request)
    {
        $isUpdated = $this->vicationService->add($request->validated());
        $message = $isUpdated
            ? 'Vacation added successfully.'
            : 'Failed to add vacation. Please try again.';

        return redirect()->route('vications.index')
            ->with('message', $message);
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
    public function update(UpdateVicationsRequest $request, string $id)
    {
        $isUpdated = $this->vicationService->update($request->validated(), $id);
        $message = $isUpdated
            ? 'Vacation updated successfully.'
            : 'Failed to update vacation. Please try again.';

        return redirect()->route('vications.index')
            ->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $isDeleted = $this->vicationService->delete($id);

        $message = $isDeleted ? 'vication deleted successfully' : '
         failed to delete vication please try again';

        return redirect()->route('vications.index')
            ->with('message', $message);
    }
}
