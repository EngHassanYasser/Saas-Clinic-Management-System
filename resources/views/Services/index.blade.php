@extends('layouts-main.dashboard')
@section('title', 'خدمات العيادة')
@section('content')
    <div x-data="ClinicServiceApp({
        serviceCatalogs: @js($serviceCatalogs),
        doctors: @js($doctors),
        clinicServices: @js($clinicServices)
    })" dir="rtl" class="p-6 bg-gray-50 min-h-screen">
        <x-clinic-services.header />
        <x-clinic-services.filters />
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <x-services.table />
        </div>
        <x-clinic-services.model />
    </div>
@endsection
