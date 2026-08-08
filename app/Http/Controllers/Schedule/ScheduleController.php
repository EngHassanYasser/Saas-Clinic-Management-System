<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use App\Services\Clinic\ClinicQueryService;
use App\Services\Doctor\DoctorQueryService;
use App\Services\Location\LocationQueryService;
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
        private LocationQueryService $locationService,
    ) {}

    public function index()
    {
        $clinicId = $this->clinicQueryService->getClinicByOwnereId(Auth::id())->id;
        [$doctors,$weekDays]= Concurrency::run([
            fn()=>$this->doctorQueryService->getAll($clinicId),
            fn()=> $this->locationService->getDays(),
        ]);
        return view('schedules.index', compact('doctors', 'weekDays'));
    }

    public function create() {}

    public function store(StoreScheduleRequest $request)
    {
        $clinicId = $this->clinicQueryService->getClinicByOwnereId(Auth::id())->id;
        $this->scheduleService->add($request->validated(), $clinicId);
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
