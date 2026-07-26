<?php

namespace App\Http\Controllers;

use App\Http\Requests\subscriptions\StoreSubscriptionRequest;
use App\Models\plan;
use App\Services\ClinicService;
use App\services\SubscriptionService;
use Illuminate\Http\Request;

class subscriptoinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private SubscriptionService $subscriptionService,private ClinicService $clinicService) {}
    public function index()
    {
        $subscriptions =  $this->subscriptionService->getAll();
        $plans = plan::get(['id', 'name', 'monthly_price']);
        $stats = $this->subscriptionService->getStats();
        $clinics  = $this->clinicService->getAll();
        return view('subscriptions.index', compact('subscriptions', 'plans', 'stats','clinics'));
    }
    public function changeStatus($subscriptionID, $newStatus)
    {
        $isUpdated = $this->subscriptionService->changeStatus($subscriptionID, $newStatus);
        $message = $isUpdated ? 'status updated successfully' : 'failed to update status';
        return redirect()->route('subscriptions.index')->with('message', $message);
    }
    public function renew($subscriptionID)
    {
        $isRenewed = $this->subscriptionService->renew($subscriptionID);
        $message = $isRenewed ? 'subscription renewed successfully' : 'failed to isRenewed subscription';
        return redirect()->route('subscriptions.index')->with('message', $message);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $this->subscriptionService->add($request->validated());
        return redirect()->route('subscriptions.index')->with('message', 'subscription added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
