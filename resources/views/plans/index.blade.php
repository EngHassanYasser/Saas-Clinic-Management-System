@extends('layouts-main.dashboard')

@section('title', 'إدارة الباقات')

@section('content')
    <div x-data="PlansApp({
        plans: @js($plans),
        statuses: @js($statuses),
    })" class="space-y-8">
        <x-plans.header />
        <x-shared.errors />
        <x-plans.stats />
        <div class="grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
            <template x-for="plan in plans" :key="plan.id">
                <x-plans.cart />
            </template>
            <x-plans.model />
        </div>
    </div>
@endsection
