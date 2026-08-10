<?php

namespace App\Http\Controllers\Plan;

use App\DTOs\Services\Plan\StorePlanDTO;
use App\DTOs\Services\Plan\UpdatePlanDTO;
use App\Enums\EnPlanStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\plans\StorePlanRequest;
use App\Http\Requests\plans\UpdatePlanRequest;
use App\Models\Plan;
use App\Services\Plan\PlanQueryService;
use App\services\Plan\PlanService;

class PlanController extends Controller
{
    public function __construct(
        private PlanService $planService,
        private PlanQueryService $planQueryService
    ) {}

    public function index()
    {
        $this->authorize('viewAny',Plan::class);
        
        $plans = $this->planQueryService->getAll();
        $statuses = enumToArray(EnPlanStatus::class);

        return view('plans.index', compact('plans', 'statuses'));
    }

    public function store(StorePlanRequest $request)
    {
        $this->authorize('create',Plan::class);

        $dto= StorePlanDTO::fromRequest($request->validated());
        $newPlan = $this->planService->add($dto);

        $message = $newPlan ? 'plan added successfully' : 'failed to add plan';

        return redirect()->route('plans.index')->with('message', $message);
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $this->authorize('update',$plan);

        $dto=UpdatePlanDTO::fromRequest($request->validated());
        $isUpdated = $this->planService->update($dto, $plan);
        $message = $isUpdated ? 'plan updated successfully' : 'failed to update plan';

        return redirect()->route('plans.index')->with('message', $message);
    }
}
