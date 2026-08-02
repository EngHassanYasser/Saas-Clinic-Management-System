@extends('layouts-main.dashboard')

@section('content')
    <div x-data="clinicsApp({
        currentClinic: @js($currentClinic),
        cities: @js($cities),
        days:@js($days),
    })" class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">
        <x-shared.errors />
        <form :action="'{{ url('clinics') }}/' + '16'" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            @csrf
            @method('PUT')
            <!-- LOGO COLUMN -->
            <div class="w-full  flex items-center justify-center gap-3 shrink-0">
                <x-clinics.form.logo />
            </div>
            <!-- TOP -->
            <div class="p-6 sm:p-8 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row gap-10 items-start">
                    <!-- INFO COLUMN -->
                    <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-clinics.form.name />
                        <x-clinics.form.phone />
                        <x-clinics.form.email />
                        <x-clinics.form.address />
                        <x-clinics.form.cities />
                    </div>
                </div>
            </div>
            <x-clinics.form.working_hours />
            <x-clinics.form.actions />
        </form>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = function() {
                document.getElementById('preview').src = reader.result;
            };

            reader.readAsDataURL(file);
        }
    </script>
@endsection
