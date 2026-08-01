<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use App\Services\ClinicQueryService;
use App\Services\DoctorQueryService;
use App\Services\ScheduleQueryService;
use App\Services\ScheduleService;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{

    public function __construct(
        private ScheduleService $scheduleService,
        private ScheduleQueryService $scheduleQueryService,
        private DoctorQueryService $doctorQueryService,
        private ClinicQueryService $clinicQueryService,
    ) {}

    public function index()
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $doctors = $this->doctorQueryService->getAll($clinic->id);
        $weekDays = $this->scheduleQueryService->getWeekDays();
        return view('schedules.index', compact('doctors', 'weekDays'));
    }

    public function create() {}

    public function store(StoreScheduleRequest $request)
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $this->scheduleService->add($request->validated(), $clinic->id);
        return redirect()->route('schedules.index')
            ->with('message', 'تم اضافة الموعد بنجاح.');
    }
    public function update(UpdateScheduleRequest $request, int $id)
    {
        $this->scheduleService->update($request->validated(), $id);

        return redirect()
            ->route('schedules.index')
            ->with('message', 'تم تحديث الموعد بنجاح.');
    }

    public function destroy(int $id)
    {
        $isDeleted = $this->scheduleService->delete($id, Auth::user()->clinic_id);
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
