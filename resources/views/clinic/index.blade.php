@extends('layouts-main.dashboard')

@section('content')
    <div dir="rtl" x-data="clinicsApp" class="p-6 min-h-screen bg-gray-100">

        <x-clinic.header />

        <x-clinic.stats-strip />

        <x-clinic.filters />

        <x-clinic.table />

        <x-clinic.add-edit-model />

        <x-clinic.delete-model />

        <x-clinic.view-model />

        <x-clinic.toast />
    </div>
@endsection
