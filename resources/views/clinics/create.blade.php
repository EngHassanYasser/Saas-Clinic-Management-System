@extends('layouts-main.App')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-6 py-6 sm:py-10">

        <!-- Clinic Info -->
        <x-clinic.clinic-info />

        <!-- GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

            <!-- LEFT CONTENT (2 columns) -->
            <div class="lg:col-span-2 space-y-6">
                <x-clinic.about-clinic />
                <x-clinic.clinic-doctors />
                <x-clinic.clinic-prices />
            </div>

            <!-- RIGHT SIDEBAR (1 column) -->
            <div class="space-y-6 lg:sticky lg:top-6 h-fit">
                <x-clinic.clinic-booking />
            </div>
        </div>
    </div>
@endsection
