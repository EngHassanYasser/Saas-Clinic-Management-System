@extends('layouts-main.dashboard')
@section('title', 'لوحة التحكم - العيادة')
@section('content')
    <div class="p-6 min-h-screen bg-gray-50" dir="rtl">
        <x-dashboards.stat-cards />
        <x-dashboards.doctors-activity />
        <x-dashboards.complains />
    </div>
@endsection
