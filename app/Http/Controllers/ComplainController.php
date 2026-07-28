<?php

namespace App\Http\Controllers;

use App\Http\Requests\complains\StoreComplainRequest;
use App\Http\Requests\complains\UpdateComplainRequest;
use App\services\complainService;
use App\services\DoctorService;
use Illuminate\Support\Facades\Auth;

class ComplainController extends Controller
{
    public function __construct(
        private complainService $complainService,
        private DoctorService $doctorService
    ) {}
    public function index()
    {
        $complaints = $this->complainService->getClinicComplains(Auth::user()->clinic_id);
        $stats = $this->complainService->getStatistics();
        $doctors = $this->doctorService->getDoctorsNames(Auth::user()->clinic_id);
        return view('complains.index', compact('complaints', 'stats', 'doctors'));
    }
    public function store(StoreComplainRequest $request)
    {
        $complain = $this->complainService->add($request->validated());
        $message = $complain
            ? 'complain added successfully.'
            : 'Failed to add complain. Please try again.';

        return redirect()->route('complains.index')->with('message', $message);
    }
    public function update(UpdateComplainRequest $request, string $id)
    {
        $isUpdated = $this->complainService->update($request->validated(), $id);
        $message = $isUpdated
            ? 'complain updated successfully.'
            : 'Failed to update complain. Please try again.';

        return redirect()->route('complains.index')->with('message', $message);
    }
    public function destroy(string $id)
    {
        $isDeleted = $this->complainService->delete($id);
        $message = $isDeleted
            ? 'complain deleted successfully.'
            : 'Failed to delete complain. Please try again.';

        return redirect()->route('complains.index')->with('message', $message);
    }
}
