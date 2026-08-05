@extends('layouts-main.dashboard')
@section('title', 'لوحة التحكم - العيادة')
@section('content')
    <div x-data="DashboardApp({
        stats: @js($stats),
        lastActivities: @js($lastActivities)
    })" class="p-6 min-h-screen bg-gray-50" dir="rtl">
        @if (auth()->user()->type == \App\Enums\RoleType::CLINIC)
            <x-dashboards.stat-cards />
            <x-dashboards.doctors-activity />
            <x-dashboards.complains />
        @elseif(auth()->user()->type === \App\Enums\RoleType::SUPER_ADMIN)
            <x-dashboards.primary-stats />
            <x-dashboards.recent-activity />
        @else
            <main class="flex-1 overflow-x-hidden">
                <div class="p-4 sm:p-8 space-y-6">
                    <x-appointments.status-row />
                    <x-appointments.filter-tabs />
                    <x-shared.errors />
                    <x-appointments.empty_cart />
                    <div class="space-y-4">
                        <template x-for="appointment in appointments" :key="appointment.id">
                            <div>
                                <x-appointments.cart />
                            </div>
                        </template>
                    </div>
                </div>
            </main>
            <x-appointments.cancel-confirm-model />
            <x-appointments.reschedule />
        @endif
    </div>
@endsection
