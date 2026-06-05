@php
    $layout = in_array(auth()->user()->type, ['clinic', 'patient']) ? 'layouts-main.dashboard' : 'layouts-main.App';
@endphp

@extends($layout)
@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-6 py-6 sm:py-10">
        @if (auth()->user()->type == 'patient')
            <!-- Clinic Info -->
            <x-clinics.clinic-info />
        @endif
        <!-- GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

            <!-- LEFT CONTENT (2 columns) -->
            <div class="lg:col-span-2 space-y-6">
                @if (auth()->user()->type == 'patient')
                    <x-clinics.about-clinic />
                @endif
                <x-clinics.clinic-doctors />
                <x-clinics.clinic-prices />
            </div>

            <!-- RIGHT SIDEBAR (1 column) -->
            <div class="space-y-6 lg:sticky lg:top-6 h-fit">
                <x-clinics.clinic-booking />
            </div>
        </div>
    </div>
@endsection
