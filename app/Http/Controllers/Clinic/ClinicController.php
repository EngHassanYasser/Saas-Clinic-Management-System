<?php

namespace App\http\Controllers\Clinic;

use App\DTOs\Services\Clinic\StoreClinicDTO;
use App\DTOs\Services\Clinic\UpdateClinicDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Clinic\StoreClinicRequest;
use App\Http\Requests\Clinic\UpdateClinicRequest;
use App\Models\Clinic;
use App\Services\Clinic\ClinicQueryService;
use App\services\Clinic\DoctorService;
use App\Services\Clinic\ClinicStatisticsService;
use App\Services\Location\LocationQueryService;
use App\Services\Plan\PlanQueryService;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Concurrency;

class ClinicController extends Controller
{
    public function __construct(
        private DoctorService $clinicService,
        private ClinicQueryService $clinicQueryService,
        private ClinicStatisticsService $clinicStatisticsService,
        private LocationQueryService $locationQueryService,
        private TenantContext $tenantContext,
        private PlanQueryService $planQueryService,
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Clinic::class);

        [$stats,$clinics,$cities,$plans] = Concurrency::run([
            fn () => $this->clinicStatisticsService->getStats(),
            fn () => $this->clinicQueryService->getAll(),
            fn () => $this->locationQueryService->getCities(),
            fn () => $this->planQueryService->getAll(),
        ]);

        return view('clinics.index', compact('clinics', 'cities', 'plans', 'stats'));
    }

    public function searchResults()
    {
        return view('clinics.SearchResults');
    }

    public function store(StoreClinicRequest $request)
    {
        $this->authorize('create', Clinic::class);
        $dto=StoreClinicDTO::fromRequest($request->validated());
        $this->clinicService->add($dto);

        return redirect()->route('clinics.index')->with('message', 'clinic added successfully');
    }

    public function edit()
    {
        $this->authorize('update', Clinic::class);

        $currentClinic = $this->tenantContext->id();

        [$cities,$days] = Concurrency::run([
            fn () => $this->locationQueryService->getCities(),
            fn () => $this->locationQueryService->getDays(),
        ]);

        return view('clinics.edit', compact('currentClinic', 'cities', 'days'));
    }

    public function update(UpdateClinicRequest $request, Clinic $clinic)
    {
        $this->authorize('update',$clinic);
        $dto=UpdateClinicDTO::fromRequest($request->validated());
        $this->clinicService->update($dto, $clinic);

        return redirect()->route('clinics.edit')->with('message', 'clinic updated successfully');
    }

    public function destroy(Clinic $clinic)
    {
        $this->authorize('delete', $clinic);

        $isDeleted = $this->clinicService->delete($clinic);
        $message = $isDeleted ? 'clinic deleted duccessfully' : 'failed to delete clinic';

        return redirect()->route('clinics.index')->with('message', $message);
    }
}
