@extends('layouts-main.dashboard')
@section('title', 'مواعيد الأطباء')
@section('content')
    <div x-data="ScheduleApp({ doctors: @js($doctors), weekDays: @js($weekDays) })" class="p-6 min-h-screen bg-gray-50" dir="rtl">
        <div class="flex flex-col gap-4">
            <x-shared.errors/>
            <template x-for="doctor in doctors" :key="doctor.id">
                <div x-data="{ open: false }">
                    <x-schedules.doctor-row />
                    <x-schedules.schedules-table />
                </div>
            </template>
            <template x-if="doctors.length === 0">
                <div>
                    <x-schedules.empty-doctors-cart />
                </div>
            </template>
            <x-schedules.model />
        </div>
    </div>
@endsection
