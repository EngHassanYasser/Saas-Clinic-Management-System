@extends('layouts-main.dashboard')

@section('title', 'لوحة التحكم - العيادة')

@section('content')

    <div class="p-6 min-h-screen bg-gray-50" dir="rtl">

        {{-- ===================== HEADER ===================== --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-medium text-gray-800">مرحبا {{ auth()->user()->name }} 👋</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ \Carbon\Carbon::now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}</p>
            </div>

        </div>
        @if (auth()->user()->type == 'clinic')
            <x-dashboard.stat-cards />
            <x-dashboard.doctors-activity />
            <x-dashboard.complains />
        @endif
    </div>

@endsection
