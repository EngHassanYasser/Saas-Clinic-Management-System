@extends('layouts-main.dashboard')
@section('title', 'الإجازات')
@section('content')
<div class="p-6 min-h-screen bg-gray-50" dir="rtl" x-data="VicationApp({
    vications: @js($vications->items()),
    doctors: @js($doctors),
    stats: @js($stats)
})">
    <x-vications.header />
    <x-vications.stats />
    <x-shared.errors />
    <x-vications.filters />
    <x-vications.table />
    <x-vications.model />
@endsection
