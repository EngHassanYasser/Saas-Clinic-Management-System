<?php

namespace App\Http\Controllers;

use App\Http\Requests\complains\StoreComplainRequest;
use App\Http\Requests\complains\UpdateComplainRequest;
use App\Models\Clinic;
use App\Services\ClinicQueryService;
use App\Services\ComplainQueryService;
use App\services\ComplainService;
use App\Services\ComplainStatisticsService;
use App\Services\DoctorQueryService;
use App\services\DoctorService;
use Illuminate\Support\Facades\Auth;

class ComplainController extends Controller
{
    public function __construct(
        private ComplainService $complainService,
        private DoctorService $doctorService,
        private ComplainQueryService $complainQueryService,
        private ComplainStatisticsService $comaplainStatisticsService,
        private DoctorQueryService $doctorQueryService,
        private ClinicQueryService $clinicQueryService,
    ) {}
    public function index()
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $complaints = $this->complainQueryService->getClinicComplains(Auth::user());
        $stats = $this->comaplainStatisticsService->getStatistics($clinic->id);
        $doctors = $this->doctorQueryService->getDoctorsNames(Clinic::where('owner_id', Auth::id())->value('id'));
        return view('complains.index', compact('complaints', 'stats', 'doctors'));
    }
    public function store(StoreComplainRequest $request)
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());

        $complain = $this->complainService->add($request->validated(), Auth::user(), $clinic->id);
        $message = $complain
            ? 'complain added successfully.'
            : 'Failed to add complain. Please try again.';

        return redirect()->route('complains.index')->with('message', $message);
    }
    public function update(UpdateComplainRequest $request, int $complainId)
    {
         $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $isUpdated = $this->complainService->update($request->validated(), $complainId,$clinic->id);
        $message = $isUpdated
            ? 'complain updated successfully.'
            : 'Failed to update complain. Please try again.';

        return redirect()->route('complains.index')->with('message', $message);
    }
    public function destroy(int $id)
    {
        $isDeleted = $this->complainService->delete($id);
        $message = $isDeleted
            ? 'complain deleted successfully.'
            : 'Failed to delete complain. Please try again.';

        return redirect()->route('complains.index')->with('message', $message);
    }
}
