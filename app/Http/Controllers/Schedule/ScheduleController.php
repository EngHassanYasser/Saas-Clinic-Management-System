<?php

namespace App\Http\Controllers\Schedule;

use App\DTOs\Services\Schedule\StoreScheduleDTO;
use App\DTOs\Services\Schedule\UpdateScheduleDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use App\Models\Schedule;
use App\Services\Doctor\DoctorQueryService;
use App\Services\Location\LocationQueryService;
use App\Services\Schedule\ScheduleService;
use App\Support\TenantContext;

class ScheduleController extends Controller
{
    public function __construct(
        private ScheduleService $scheduleService,
        private DoctorQueryService $doctorQueryService,
        private LocationQueryService $locationService,
        private TenantContext $tenantContext,
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Schedule::class);

        $clinicId = $this->tenantContext->id();
        $doctors = $this->doctorQueryService->getAll($clinicId);
        $weekDays = $this->locationService->getDays();
        return view('schedules.index', compact('doctors', 'weekDays'));
    }

    public function store(StoreScheduleRequest $request)
    {
        $this->authorize('create', Schedule::class);

        $clinicId = $this->tenantContext->id();
        $dto = StoreScheduleDTO::fromRequest($request->validated());

        $this->scheduleService->add($dto, $clinicId);

        return redirect()->route('schedules.index')
            ->with('message', 'تم اضافة الموعد بنجاح.');
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        $dto = UpdateScheduleDTO::fromRequest($request->validated());
        $this->scheduleService->update($dto, $schedule);

        return redirect()
            ->route('schedules.index')
            ->with('message', 'تم تحديث الموعد بنجاح.');
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);

        $isDeleted = $this->scheduleService->delete($schedule);
        if ($isDeleted == false) {
            return redirect()
                ->route('schedules.index')
                ->with('message', 'فشل الحذف الرجاء المحاوله لاحقا');
        }

        return redirect()
            ->route('schedules.index')
            ->with('message', 'تم الحذف بنجاح');
    }
}
