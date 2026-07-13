<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    private ScheduleService $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function index()
    {
        $doctors = $this->scheduleService->getAll();
        $weekDays = $this->scheduleService->getWeekDays();
        return view('schedules.index', compact('doctors', 'weekDays'));
    }

    public function create() {}

    public function store(StoreScheduleRequest $request)
    {
        $this->scheduleService->addNew($request->validated());
        return redirect()->route('schedules.index')
            ->with('message', 'تم اضافة الموعد بنجاح.');
    }

    public function show(string $id) {dd('show');}

    public function edit(string $id) {dd('edite');}

    public function update(UpdateScheduleRequest $request, string $id)
    {
        dd('edite');
        $this->scheduleService->update($request->validated(), $id);

        return redirect()
            ->route('schedules.index')
            ->with('message', 'تم تحديث الموعد بنجاح.');
    }

    public function destroy(string $id)
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
