@extends('layouts-main.dashboard')

@section('content')
    <div dir="rtl" x-data="clinicsApp" class="p-6 min-h-screen bg-gray-100">

        <x-clinics.header />

        <x-clinics.stats-strip />

        <x-clinics.filters />

        <x-clinics.table />

        <x-clinics.add-edit-model />

        <x-clinics.delete-model />

        <x-clinics.view-model />

        <x-clinics.toast />
    </div>
@endsection
