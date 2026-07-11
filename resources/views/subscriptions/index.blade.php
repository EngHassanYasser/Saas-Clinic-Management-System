@extends('layouts-main.dashboard')

@section('title', 'إدارة الاشتراكات')

@section('content')

    <div dir="rtl" class="p-6 bg-gray-50 min-h-screen" x-data="subscriptionsApp({
        subscriptions: @js($subscriptions)
    })">
        <x-subscriptions.header />

        <x-subscriptions.kpi-cards />
        <x-subscriptions.filters />
        <x-subscriptions.table />

        <x-subscriptions.model />

    </div>
@endsection
