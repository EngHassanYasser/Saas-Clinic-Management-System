<?php

namespace App\Http\Controllers;

use App\Http\Requests\complains\StoreComplainRequest;
use App\services\complainService;
use App\services\DoctorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private complainService $complainService, private DoctorService $doctorService) {}
    public function index()
    {
        $complaints = $this->complainService->getClinicComplains(Auth::user()->clinic_id);
        $stats = $this->complainService->getStatistics();
        $doctors = $this->doctorService->getDoctorsNames(Auth::user()->clinic_id);

        return view('complains.index', compact('complaints', 'stats', 'doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreComplainRequest $request)
    {
        $complain = $this->complainService->add($request->validated());
        $message = $complain
            ? 'complain added successfully.'
            : 'Failed to add complain. Please try again.';

        return view('complains.index')
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
    public function edit(string $id) {}

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
        $isDeleted = $this->complainService->delete($id);
        $message = $isDeleted
            ? 'complain deleted successfully.'
            : 'Failed to delete complain. Please try again.';

        return redirect()->route('complains.index')
            ->with('message', $message);
    }
}
