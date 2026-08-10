<?php

namespace App\Http\Controllers\Vacation;

use App\DTOs\Services\Vacation\StoreVacationDTO;
use App\DTOs\Services\Vacation\UpdateVacationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\vacations\StoreVacationsRequest;
use App\Http\Requests\vacations\UpdateVacationsRequest;
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

        [$vacations,$doctors,$stats] = Concurrency::run([
            fn () => $this->vactionQueryService->getClinicVacations($clinicId),
            fn () => $this->doctorQueryService->getDoctorsNames($clinicId),
            fn () => $this->vacationStatisticsService->getStatistics($clinicId),
        ]);

        return view('vacations.index', compact('vacations', 'doctors', 'stats'));
    }

    public function store(StoreVacationsRequest $request)
    {
        $clinicId = $this->tenantContext->id();
        $dto=StoreVacationDTO::fromRequest($request->validatd());
        $isUpdated = $this->vacationService->add($dto, $clinicId);
        $message = $isUpdated
            ? 'Vacation added successfully.'
            : 'Failed to add vacation. Please try again.';

        return redirect()->route('vacations.index')
            ->with('message', $message);
    }

    public function update(UpdateVacationsRequest $request, int $vacationId)
    {
        $clinicId = $this->tenantContext->id();
        $dto= UpdateVacationDTO::fromRequest($request->validated());
        $isUpdated = $this->vacationService->update($dto, $vacationId, $clinicId);
        $message = $isUpdated
            ? 'Vacation updated successfully.'
            : 'Failed to update vacation. Please try again.';

        return redirect()->route('vacations.index')
            ->with('message', $message);
    }

    public function destroy(int $vacationId)
    {
        $clinicId = $this->tenantContext->id();
        $isDeleted = $this->vacationService->delete($vacationId, $clinicId);

        $message = $isDeleted ? 'vacation deleted successfully' : '
         failed to delete vacation please try again';

        return redirect()->route('vacations.index')
            ->with('message', $message);
    }
}
