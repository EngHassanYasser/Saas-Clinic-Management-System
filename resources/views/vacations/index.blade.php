@extends('layouts-main.dashboard')
@section('title', 'الإجازات')
@section('content')
<div class="p-6 min-h-screen bg-gray-50" dir="rtl" x-data="VacationApp({
    vacations: @js($vacations->items()),
    doctors: @js($doctors),
    stats: @js($stats)
})">
    <x-vacations.header />
    <x-vacations.stats />
    <x-shared.errors />
    <x-vacations.filters />
    <x-vacations.table />
    <x-vacations.model />
@endsection
