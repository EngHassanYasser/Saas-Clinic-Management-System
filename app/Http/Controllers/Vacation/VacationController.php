<?php

namespace App\Http\Controllers;

use App\Http\Requests\vications\StoreVicationsRequest;
use App\Http\Requests\vications\UpdateVicationsRequest;
use App\Services\ClinicQueryService;
use App\Services\DoctorQueryService;
use App\Services\VacationQueryService;
use App\Services\VacationService;
use App\Services\VacationStatisticsService;
use Illuminate\Support\Facades\Auth;

class VacationController extends Controller
{
    public function __construct(
        private VacationService $vacationService,
        private VacationStatisticsService $vacationStatisticsService,
        private VacationQueryService $vactionQueryService,
        private DoctorQueryService $doctorQueryService,
        private ClinicQueryService $clinicQueryService,
    ) {}

    public function index()
    {
        $vications = $this->vactionQueryService->getClinicVacations(Auth::user()->clinic_id);
        $doctors = $this->doctorQueryService->getDoctorsNames(Auth::user()->clinic_id);
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $stats = $this->vacationStatisticsService->getStatistics($clinic->id);

        return view('vacations.index', compact('vications', 'doctors', 'stats'));
    }

    public function store(StoreVicationsRequest $request)
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $isUpdated = $this->vacationService->add($request->validated(), $clinic->id);
        $message = $isUpdated
            ? 'Vacation added successfully.'
            : 'Failed to add vacation. Please try again.';

        return redirect()->route('vications.index')
            ->with('message', $message);
    }

    public function update(UpdateVicationsRequest $request, int $vacationId)
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $isUpdated = $this->vacationService->update($request->validated(), $vacationId, $clinic->id);
        $message = $isUpdated
            ? 'Vacation updated successfully.'
            : 'Failed to update vacation. Please try again.';

        return redirect()->route('vications.index')
            ->with('message', $message);
    }

    public function destroy(int $vacationId)
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $isDeleted = $this->vacationService->delete($vacationId, $clinic->id);

        $message = $isDeleted ? 'vication deleted successfully' : '
         failed to delete vication please try again';

        return redirect()->route('vications.index')
            ->with('message', $message);
    }
}
