@extends('layouts-main.dashboard')

@section('title', 'الشكاوى')

@section('content')
    <div class="p-6 min-h-screen bg-gray-50" dir="rtl" x-data="complaintsForm({
        complaints: @js($complaints),
        stats: @js($stats),
        doctors:@js($doctors),
    })">
     @if (auth()->user()->type === 'clinic')
            <x-complains.status />
        @endif
        <x-complains.add-button />
        <x-shared.errors/>
        <x-complains.filters />
        <x-complains.table />
        <x-complains.model/>
    </div>
@endsection
