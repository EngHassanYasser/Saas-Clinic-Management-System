@extends('layouts-main.App')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-10">

        <!-- Header -->
        <div class="mb-8">
            <x-clinic.search />
            <p class="text-center text-gray-500 mt-1">
                تم العثور على نتائج مناسبة بناءً على بحثك
            </p>
            <!-- List -->
            <div class="space-y-5">
                <x-clinic.clinic-search-cart />
            </div>

        </div>
    @endsection
