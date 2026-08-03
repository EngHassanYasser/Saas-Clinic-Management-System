<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use App\Services\Clinic\ClinicQueryService;
use App\Services\Doctor\DoctorQueryService;
use App\Services\Schedule\ScheduleQueryService;
use App\Services\Schedule\ScheduleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Concurrency;

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
        [$doctors,$weekDays]= Concurrency::run([
            fn()=>$this->doctorQueryService->getAll($clinic->id),
            fn()=> $this->scheduleQueryService->getWeekDays(),
        ]);
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
