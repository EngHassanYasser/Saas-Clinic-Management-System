@extends('layouts-main.dashboard')
@section('title', 'لوحة التحكم - العيادة')
@section('content')
    <div x-data="DashboardApp({
        stats: @js($stats),
        lastActivities: @js($lastActivities)
    })" class="p-6 min-h-screen bg-gray-50" dir="rtl">
        @if (auth()->user()->type == \App\Enums\RoleType::CLINIC->value)
            <x-dashboards.stat-cards />
            <x-dashboards.doctors-activity />
            <x-dashboards.complains />
        @elseif(auth()->user()->type === \App\Enums\RoleType::SUPER_ADMIN->value)
            <x-dashboards.primary-stats />
            <x-dashboards.recent-activity />
        @endif
    </div>
@endsection
