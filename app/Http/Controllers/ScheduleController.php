<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

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
        return $this->scheduleService->addNew($request->validated());
    }

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
