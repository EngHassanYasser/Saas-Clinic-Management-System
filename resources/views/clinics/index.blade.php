@extends('layouts-main.dashboard')
@section('content')
    <div dir="rtl" x-data="ClinicsApp({
        clinics: @js($clinics),
        cities: @js($cities),
        plans: @js($plans),
        stats:@js($stats),
    })" class="p-6 min-h-screen bg-gray-100">
        <x-clinics.header />
        <x-clinics.stats-strip />
        <x-clinics.filters />
        <x-clinics.table />
        <x-clinics.model />
        <x-clinics.toast />
    </div>
@endsection