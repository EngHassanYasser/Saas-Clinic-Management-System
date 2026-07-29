@php
    $layout = auth()->user()->usesDashboardLayout() ? 'layouts-main.dashboard' : 'layouts-main.app';
@endphp
@extends($layout)

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-10">

        <!-- Header -->
        <div class="mb-8">
            <x-clinics.search />
            <p class="text-center text-gray-500 mt-1">
                تم العثور على نتائج مناسبة بناءً على بحثك
            </p>
            <!-- List -->
            <div class="space-y-5">
                <x-clinics.clinic-search-cart />
            </div>

        </div>
    @endsection
