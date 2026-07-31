@php
    $layout = auth()->user()->usesDashboardLayout() ? 'layouts-main.dashboard' : 'layouts-main.app';
@endphp

@extends($layout)
@section('content')
    <div class="max-w-3xl mx-auto px-4 py-6 sm:py-10" x-data="bookingForm({
        specialties: @js($specialities)})" x-cloak>
        <x-shared.errors/>
        <x-appointments.header />
        <x-appointments.step_progress />
        <form @submit.prevent="submitBooking"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 space-y-6">
            <x-appointments.specialty />
            <x-appointments.service />
            <x-appointments.clinic />
            <x-appointments.doctor />
            <x-appointments.date />
            <x-appointments.summary />
        </form>
       <x-appointments.submit_button />
        <x-appointments.toast />
    </div>
@endsection
