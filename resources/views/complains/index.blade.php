@extends('layouts-main.dashboard')

@section('title', 'الشكاوى')

@section('content')
    <div class="p-6 min-h-screen bg-gray-50" dir="rtl" x-data="complaintsForm({
        complaints: @js($complaints)})">

        <x-complains.add-button />

        @if (auth()->user()->type === 'clinic')
            <x-complains.status />
        @endif
        <x-complains.filters />
        <x-complains.table />
    </div>

    <x-complains.details-model />
    <x-complains.delete-model />
@endsection
