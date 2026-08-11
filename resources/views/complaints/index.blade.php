@extends('layouts-main.dashboard')

@section('title', 'الشكاوى')

@section('content')
    <div class="p-6 min-h-screen bg-gray-50" dir="rtl" x-data="ComplaintApp({
        complaints: @js($complaints),
        stats: @js($stats),
        doctors:@js($doctors),
    })">
     @if (auth()->user()->type === 'clinic')
            <x-complaints.status />
        @endif
        <x-complaints.add-button />
        <x-shared.errors/>
        <x-complaints.filters />
        <x-complaints.table />
        <x-complaints.model/>
    </div>
@endsection
