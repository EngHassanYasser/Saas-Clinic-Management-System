@extends('layouts-main.dashboard')

@section('title', 'لوحة التحكم - العيادة')

@section('content')

    <div class="p-6 min-h-screen bg-gray-50" dir="rtl">
        @if (auth()->user()->type == 'clinic')
            <x-dashboard.stat-cards />
            <x-dashboard.doctors-activity />
            <x-dashboard.complains />
        @endif
    </div>

@endsection
