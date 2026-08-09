<?php

namespace App\Http\Controllers\Vacation;

use App\Http\Controllers\Controller;
use App\Http\Requests\vications\StoreVicationsRequest;
use App\Http\Requests\vications\UpdateVicationsRequest;
use App\Services\Clinic\ClinicQueryService;
use App\Services\Doctor\DoctorQueryService;
use App\Services\Vacation\VacationQueryService;
use App\Services\Vacation\VacationService;
use App\Services\Vacation\VacationStatisticsService;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Concurrency;

class VacationController extends Controller
{
    public function __construct(
        private VacationService $vacationService,
        private VacationStatisticsService $vacationStatisticsService,
        private VacationQueryService $vactionQueryService,
        private DoctorQueryService $doctorQueryService,
        private TenantContext $tenantContext,
    ) {}

    public function index()
    {
        $clinicId = $this->tenantContext->id();
        $clinic = $this->tenantContext->get();

        [$vications,$doctors,$stats] = Concurrency::run([
            fn () => $this->vactionQueryService->getClinicVacations($clinicId),
            fn () => $this->doctorQueryService->getDoctorsNames($clinicId),
            fn () => $this->vacationStatisticsService->getStatistics($clinicId),
        ]);

        return view('vacations.index', compact('vications', 'doctors', 'stats'));
    }

    public function store(StoreVicationsRequest $request)
    {
        $clinicId = $this->tenantContext->id();
        $isUpdated = $this->vacationService->add($request->validated(), $clinicId);
        $message = $isUpdated
            ? 'Vacation added successfully.'
            : 'Failed to add vacation. Please try again.';

        return redirect()->route('vications.index')
            ->with('message', $message);
    }

    public function update(UpdateVicationsRequest $request, int $vacationId)
    {
        $clinicId = $this->tenantContext->id();
        $isUpdated = $this->vacationService->update($request->validated(), $vacationId, $clinicId);
        $message = $isUpdated
            ? 'Vacation updated successfully.'
            : 'Failed to update vacation. Please try again.';

        return redirect()->route('vications.index')
            ->with('message', $message);
    }

    public function destroy(int $vacationId)
    {
        $clinicId = $this->tenantContext->id();
        $isDeleted = $this->vacationService->delete($vacationId, $clinicId);

        $message = $isDeleted ? 'vication deleted successfully' : '
         failed to delete vication please try again';

        return redirect()->route('vications.index')
            ->with('message', $message);
    }
}
