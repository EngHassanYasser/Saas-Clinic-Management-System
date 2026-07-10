@extends('layouts-main.dashboard')

@section('title', 'الإجازات')

@section('content')

    <div class="p-6 min-h-screen bg-gray-50" dir="rtl" x-data="vicationForm({ vications: @js($vications) })">
        <x-vications.header />
        <x-vications.stats />
        <x-vications.filters />
        <x-vications.table />
        <x-vications.model />
    @endsection
