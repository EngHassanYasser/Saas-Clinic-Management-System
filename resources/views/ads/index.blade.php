@extends('layouts-main.dashboard')
@section('content')
    <div dir="rtl" x-data="AdApp" class="p-6 bg-gray-50 min-h-screen">
        <x-ads.header />
        <x-ads.filter-tabs />
        <x-ads.ads-grid />
        <x-ads.add-edit-model />
        <x-ads.delete-model />
        <x-ads.toast />
    </div>
@endsection
