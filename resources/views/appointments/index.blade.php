@extends('layouts-main.dashboard')

@section('title', 'موعدي')

@section('content')
    <div class="flex min-h-screen" dir="rtl" x-data="bookingForm({
        appointments: @js($appointments->items()),
        stats: @js($stats)
    })">
    <button type="button" @click="console.log(appointments)">click</button>
        <main class="flex-1 overflow-x-hidden">
            <div class="p-4 sm:p-8 space-y-6">
                <x-appointments.status-row />
                <x-appointments.filter-tabs />
                <x-shared.errors />
                <x-appointments.empty_cart />
                <div class="space-y-4">
                    <template x-for="appointment in appointments" :key="appointment.id">
                        <div>
                            <x-appointments.cart />
                        </div>
                    </template>
                </div>
            </div>
        </main>
        <x-appointments.cancel-confirm-model />
        <x-appointments.reschedule />
    </div>
@endsection
