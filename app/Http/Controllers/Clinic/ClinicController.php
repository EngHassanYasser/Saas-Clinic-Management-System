<?php

namespace App\http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clinic\StoreClinicRequest;
use App\Http\Requests\Clinic\UpdateClinicRequest;
use App\Models\Clinic;
use App\Models\Plan;
use App\Services\Clinic\ClinicQueryService;
use App\services\Clinic\ClinicService;
use App\Services\Clinic\ClinicStatisticsService;
use App\Services\Location\LocationQueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Concurrency;

class ClinicController extends Controller
{
    public function __construct(
        private ClinicService $clinicService,
        private ClinicQueryService $clinicQueryService,
        private ClinicStatisticsService $clinicStatisticsService,
        private LocationQueryService $locationQueryService,
    ) {}

    public function index()
    {
        [$stats,$clinics,$citites,$plans] = Concurrency::run([
            fn () => $this->clinicStatisticsService->getStats(),
            fn () => $this->clinicQueryService->getAll(),
            fn () => $this->locationQueryService->getCities(),
            fn () => Plan::get(['id', 'name']),
        ]);

        return view('clinics.index', compact('clinics', 'cities', 'plans', 'stats'));
    }

    public function searchResults()
    {
        return view('clinics.SearchResults');
    }

    public function store(StoreClinicRequest $request)
    {
        $this->clinicService->add($request->validated());

        return redirect()->route('clinics.index')->with('message', 'clinic added successfully');
    }

    public function edit(Request $request)
    {
        $currentClinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());

        [$cities,$days] = Concurrency::run([
            fn () => $this->locationQueryService->getCities(),
            fn () => $this->locationQueryService->getDays(),
        ]);

        return view('clinics.edit', compact('currentClinic', 'cities', 'days'));
    }

    public function update(UpdateClinicRequest $request, Clinic $clinic)
    {
        $this->clinicService->update($request->validated(), $clinic);

        return redirect()->route('clinics.edit')->with('message', 'clinic updated successfully');
    }

    public function destroy(Clinic $clinic)
    {
        $isDeleted = $this->clinicService->delete($clinic);
        $message = $isDeleted ? 'clinic deleted duccessfully' : 'failed to delete clinic';

        return redirect()->route('clinics.index')->with('message', $message);
    }
}
