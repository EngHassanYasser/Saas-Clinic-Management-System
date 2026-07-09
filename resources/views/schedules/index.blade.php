@extends('layouts-main.dashboard')

@section('title', 'مواعيد الأطباء')

@section('content')
    <div x-data="{
        doctors: @js($doctors),
        weekDays: @js($weekDays),
        selectedDays: [],
        open: false,
        showModel: false,
        addMode:false,
        editeMode:false,
        editSchedule: null,
        toggleDay(id) {
            this.selectedDays.includes(id) ?
                this.selectedDays = this.selectedDays.filter(d => d !== id) :
                this.selectedDays.push(id);
        },
        openEdit(schedule) {
        this.editeMode = true;
            this.editSchedule = { ...schedule };
            this.showModel = true;
        },
         openAdd() {
            this.addMode = true;
            this.showModel = true;
        }
    }" class="p-6 min-h-screen bg-gray-50" dir="rtl">
        {{-- DOCTORS LIST --}}
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
