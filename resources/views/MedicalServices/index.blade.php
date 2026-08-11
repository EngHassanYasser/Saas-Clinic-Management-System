@extends('layouts-main.dashboard')
@section('title', 'خدمات العيادة')
@section('content')
    <div x-data="DoctorServiceApp({
        medicalService: @js($medicalService),
        doctors: @js($doctors),
        clinicServices: @js($clinicServices)
    })" dir="rtl" class="p-6 bg-gray-50 min-h-screen">
        <x-MedicalServices.header />
        <x-MedicalServices.filters />
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <x-services.table />
        </div>
        <x-MedicalServices.model />
    </div>
@endsection
