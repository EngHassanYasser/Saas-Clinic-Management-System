@extends('layouts-main.dashboard')
@section('title', 'الأطباء')
@section('content')
    <div x-data="doctorsForm({ doctors: @js($doctors), specialities: @js($specialities),stats:@js($stats) })">
        <x-doctors.stats />
         <x-shared.errors />
        <div class="flex justify-between ">
            <x-doctors.filters />
            <x-doctors.add-doctor-button />
        </div>
        <x-doctors.grid-view />
        <x-doctors.empty-state />
        <x-doctors.model />
    </div>
    </div>
@endsection
