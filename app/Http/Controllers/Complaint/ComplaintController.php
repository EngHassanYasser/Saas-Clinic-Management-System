<?php

namespace App\Http\Controllers\Complaint;

use App\DTOs\Services\Complaint\StoreComplaintDTO;
use App\DTOs\Services\Complaint\UpdateComplaintDTO;
use App\Enums\EnRoleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\complaintts\StoreComplaintRequest;
use App\Http\Requests\complaintts\UpdateComplaintRequest;
use App\Services\Complaint\ComplaintQueryService;
use App\services\Complaint\ComplaintService;
use App\Services\Complaint\ComplaintStatisticsService;
use App\Services\Doctor\DoctorQueryService;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Concurrency;

class ComplaintController extends Controller
{
    public function __construct(
        private ComplaintService $complaintService,
        private ComplaintQueryService $complaintQueryService,
        private ComplaintStatisticsService $comaplainStatisticsService,
        private DoctorQueryService $doctorQueryService,
        private TenantContext $tenantContext,
    ) {}

    public function index()
    {
        if (Auth::user()->type ==EnRoleType::CLINIC->value) {
            $clinicId = $this->tenantContext->id();
            $complaintts = $this->complaintQueryService->getClinicComplaints(Auth::user());

            [$stats,$doctors] = Concurrency::run([
                fn () => $this->comaplainStatisticsService->getStatistics($clinicId),
                fn () => $this->doctorQueryService->getDoctorsNames($clinicId),
            ]);
        } else {
            $complaintts = $this->complaintQueryService->getClinicComplaints(Auth::user());
            $stats = [];
            $doctors = [];
        }

        return view('complaintts.index', compact('complaintts', 'stats', 'doctors'));
    }

    public function store(StoreComplaintRequest $request)
    {
        $clinicId = $this->tenantContext->id();
        $dto = StoreComplaintDTO::fromRequest($request->validated());
        $complaint = $this->complaintService->add($dto, Auth::user(), $clinicId);
        $message = $complaint
            ? 'complaint added successfully.'
            : 'Failed to add complaint. Please try again.';

        return redirect()->route('complaintts.index')->with('message', $message);
    }

    public function update(UpdateComplaintRequest $request, int $complaintId)
    {
        $clinicId = $this->tenantContext->id();
        $dto=UpdateComplaintDTO::fromRequest($request->validated());
        $isUpdated = $this->complaintService->update($dto, $complaintId, $clinicId);
        $message = $isUpdated
            ? 'complaint updated successfully.'
            : 'Failed to update complaint. Please try again.';

        return redirect()->route('complaintts.index')->with('message', $message);
    }

    public function destroy(int $complaintId)
    {
        $isDeleted = $this->complaintService->delete($complaintId);
        $message = $isDeleted
            ? 'complaint deleted successfully.'
            : 'Failed to delete complaint. Please try again.';

        return redirect()->route('complaintts.index')->with('message', $message);
    }
}
