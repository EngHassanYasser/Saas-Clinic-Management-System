@extends('layouts-main.dashboard')

@section('title', 'مواعيد الأطباء')

@section('content')
    <div x-data="schedulesForm({doctors: @js($doctors),weekDays: @js($weekDays)})"
     class="p-6 min-h-screen bg-gray-50" dir="rtl">
        <div class="flex flex-col gap-4">

            @forelse ($doctors as $doctor)
                <x-schedules.doctor-row :$doctor />

                <x-schedules.schedules-table :$doctor />

                <x-schedules.model :$doctor />

        </div>
    @empty
        <x-schedules.empty-doctors-cart />
        @endforelse
    </div>
    </div>
@endsection
