<?php

namespace App\Http\Controllers\Complain;

use App\Enums\RoleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\complains\StoreComplainRequest;
use App\Http\Requests\complains\UpdateComplainRequest;
use App\Services\Complain\ComplainQueryService;
use App\services\Complain\ComplainService;
use App\Services\Complain\ComplainStatisticsService;
use App\Services\Doctor\DoctorQueryService;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Concurrency;

class ComplainController extends Controller
{
    public function __construct(
        private ComplainService $complainService,
        private ComplainQueryService $complainQueryService,
        private ComplainStatisticsService $comaplainStatisticsService,
        private DoctorQueryService $doctorQueryService,
        private TenantContext $tenantContext,
    ) {}

    public function index()
    {
        if (Auth::user()->type == RoleType::CLINIC->value) {
            $clinicId = $this->tenantContext->id();
            $complaints = $this->complainQueryService->getClinicComplains(Auth::user());

            [$stats,$doctors] = Concurrency::run([
                fn () => $this->comaplainStatisticsService->getStatistics($clinicId),
                fn () => $this->doctorQueryService->getDoctorsNames($clinicId),
            ]);
        } else {
            $complaints = $this->complainQueryService->getClinicComplains(Auth::user());
            $stats = [];
            $doctors = [];
        }

        return view('complains.index', compact('complaints', 'stats', 'doctors'));
    }

    public function store(StoreComplainRequest $request)
    {
        $clinicId = $this->tenantContext->id();

        $complain = $this->complainService->add($request->validated(), Auth::user(), $clinicId);
        $message = $complain
            ? 'complain added successfully.'
            : 'Failed to add complain. Please try again.';

        return redirect()->route('complains.index')->with('message', $message);
    }

    public function update(UpdateComplainRequest $request, int $complainId)
    {
        $clinicId = $this->tenantContext->id();
        $isUpdated = $this->complainService->update($request->validated(), $complainId, $clinicId);
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
