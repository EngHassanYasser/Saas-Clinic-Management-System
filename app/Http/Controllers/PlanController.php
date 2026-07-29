<?php

namespace App\Http\Controllers;

use App\Enums\PlanStatus;
use App\Http\Requests\plans\StorePlanRequest;
use App\Http\Requests\plans\UpdatePlanRequest;
use App\services\PlanService;

class PlanController extends Controller
{
    public function __construct(private PlanService $planService) {}
    public function index()
    {
        $plans = $this->planService->getAll();
        $statuses = enumToArray(PlanStatus::class);
        return view('plans.index', compact('plans', 'statuses'));
    }
    public function store(StorePlanRequest $request)
    {
        $newPlan = $this->planService->add($request->validated());

        $message = $newPlan ? 'plan added successfully' : 'failed to add plan';
        return redirect()->route('plans.index')->with('message', $message);
    }
    public function update(UpdatePlanRequest $request, string $id)
    {
        $isUpdated = $this->planService->update($request->validated(), $id);
        $message = $isUpdated ? 'plan updated successfully' : 'failed to update plan';

        return redirect()->route('plans.index')->with('message', $message);
    }
}
