@extends('layouts-main.dashboard')
@section('title', 'لوحة التحكم - العيادة')
@section('content')
    <div class="p-6 min-h-screen bg-gray-50" dir="rtl">
        @if (auth()->user()->type == 'clinic')
            <x-dashboards.stat-cards />
            <x-dashboards.doctors-activity />
            <x-dashboards.complains />
        @elseif(auth()->user()->type == 'super_admin')
            <x-dashboards.header />
            <x-dashboards.primary-stats />
            <x-dashboards.secoundry-stats />
            <x-dashboards.recent-activity />
        @endif
    </div>
@endsection
