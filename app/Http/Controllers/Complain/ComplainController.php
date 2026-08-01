<?php

namespace App\Http\Controllers\Complain;

use App\Enums\RoleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\complains\StoreComplainRequest;
use App\Http\Requests\complains\UpdateComplainRequest;
use App\Services\Clinic\ClinicQueryService;
use App\Services\Complain\ComplainQueryService;
use App\services\Complain\ComplainService;
use App\Services\Complain\ComplainStatisticsService;
use App\Services\Doctor\DoctorQueryService;
use Illuminate\Support\Facades\Auth;

class ComplainController extends Controller
{
    public function __construct(
        private ComplainService $complainService,
        private ComplainQueryService $complainQueryService,
        private ComplainStatisticsService $comaplainStatisticsService,
        private DoctorQueryService $doctorQueryService,
        private ClinicQueryService $clinicQueryService,
    ) {}
    public function index()
    {
        if (Auth::user()->type == RoleType::CLINIC->value) {
            $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
            $complaints = $this->complainQueryService->getClinicComplains(Auth::user());
            $stats = $this->comaplainStatisticsService->getStatistics(1);
            $doctors = $this->doctorQueryService->getDoctorsNames(1);
        } else {
            $complaints = $this->complainQueryService->getClinicComplains(Auth::user());
            $stats = [];
            $doctors =[];
        }

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
        $isUpdated = $this->complainService->update($request->validated(), $complainId, $clinic->id);
        $message = $isUpdated
            ? 'complain updated successfully.'
            : 'Failed to update complain. Please try again.';

        return redirect()->route('complains.index')->with('message', $message);
    }
    public function destroy(int $complainId)
    {
        $isDeleted = $this->complainService->delete($complainId);
        $message = $isDeleted
            ? 'complain deleted successfully.'
            : 'Failed to delete complain. Please try again.';

        return redirect()->route('complains.index')->with('message', $message);
    }
}
