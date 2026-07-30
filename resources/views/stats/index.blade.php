@extends('layouts-main.dashboard')
@section('title', 'الإحصائيات وأخر النشاطات')
@section('content')
    <div x-data="DashboardApp({
        stats: @js($stats),
        lastActivities: @js($lastActivities)
    })" class="p-6 min-h-screen bg-gray-50" dir="rtl">
        <x-dashboards.primary-stats />
        <x-dashboards.recent-activity />
    </div>
@endsection
