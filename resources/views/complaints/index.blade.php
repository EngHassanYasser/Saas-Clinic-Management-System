@extends('layouts-main.dashboard')

@section('title', 'الشكاوى')

@section('content')
    <div class="p-6 min-h-screen bg-gray-50" dir="rtl" x-data="ComplainttApp({
        complaintts: @js($complaintts),
        stats: @js($stats),
        doctors:@js($doctors),
    })">
     @if (auth()->user()->type === 'clinic')
            <x-complaintts.status />
        @endif
        <x-complaintts.add-button />
        <x-shared.errors/>
        <x-complaintts.filters />
        <x-complaintts.table />
        <x-complaintts.model/>
    </div>
@endsection
