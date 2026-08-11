<?php

namespace App\Http\Controllers\Complaint;

use App\DTOs\Services\Complaint\StoreComplaintDTO;
use App\DTOs\Services\Complaint\UpdateComplaintDTO;
use App\Enums\EnRoleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\complaints\StoreComplaintRequest;
use App\Http\Requests\complaints\UpdateComplaintRequest;
use App\Models\Complaint;
use App\Services\Clinic\ClinicQueryService;
use App\Services\Complaint\ComplaintQueryService;
use App\services\Complaint\ComplaintService;
use App\Services\Complaint\ComplaintStatisticsService;
use App\Services\Doctor\DoctorQueryService;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function __construct(
        private ComplaintService $complaintService,
        private ComplaintQueryService $complaintQueryService,
        private ComplaintStatisticsService $comaplainStatisticsService,
        private DoctorQueryService $doctorQueryService,
        private TenantContext $tenantContext,
        private ClinicQueryService $clincQueryService,
    ) {}

    public function index()
    {
        $stats = [];
        $doctors = [];
        $this->authorize('viewAny', Complaint::class);

        $complaints = $this->complaintQueryService->getClinicComplaints(Auth::user());
        if (Auth::user()->type == EnRoleType::CLINIC) {
            $clinicId = $this->clincQueryService->getClinicByOwnereId(Auth::id())->id;
            $stats = $this->comaplainStatisticsService->getStatistics($clinicId);
            $doctors = $this->doctorQueryService->getDoctorsNames($clinicId);
        }

        return view('complaints.index', compact('complaints', 'stats', 'doctors'));
    }

    public function store(StoreComplaintRequest $request)
    {
        $this->authorize('create', Complaint::class);

        $clinicId = $this->tenantContext->id();
        $dto = StoreComplaintDTO::fromRequest($request->validated());
        $complaint = $this->complaintService->add($dto, Auth::user(), $clinicId);
        $message = $complaint
            ? 'complaint added successfully.'
            : 'Failed to add complaint. Please try again.';

        return redirect()->route('complaints.index')->with('message', $message);
    }

    public function update(UpdateComplaintRequest $request, Complaint $complaint)
    {
        $this->authorize('update', $complaint);

        $dto = UpdateComplaintDTO::fromRequest($request->validated());
        $isUpdated = $this->complaintService->update($dto, $complaint);
        $message = $isUpdated
            ? 'complaint updated successfully.'
            : 'Failed to update complaint. Please try again.';

        return redirect()->route('complaints.index')->with('message', $message);
    }

    public function destroy(Complaint $complaint)
    {
        $this->authorize('delete', $complaint);

        $isDeleted = $this->complaintService->delete($complaint);
        $message = $isDeleted
            ? 'complaint deleted successfully.'
            : 'Failed to delete complaint. Please try again.';

        return redirect()->route('complaints.index')->with('message', $message);
    }
}
