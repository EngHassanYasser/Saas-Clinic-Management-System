@extends('layouts-main.dashboard')
@section('content')
    <div class="min-h-screen bg-slate-50 py-8" x-data="profileImage()">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition
                    class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                    <div class="flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button type="button" @click="show=false" class="font-bold">
                            ×
                        </button>
                    </div>
                </div>
            @endif
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Cover -->
                    <div class="h-40 bg-gradient-to-r from-cyan-600 via-teal-600 to-cyan-700">
                    </div>
                    <div class="px-6 md:px-10 pb-10">
                        <x-profile.avatar />
                        <!-- Form Fields -->
                        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-profile.name />
                            <x-profile.email />
                            <x-profile.phone />
                            <x-profile.address />
                        </div>
                        <x-profile.actions />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function profileImage() {
            return {
                preview: @js(auth()->user()->image ? asset('storage/' . auth()->user()->image) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name)),

                changeImage(event) {
                    const file = event.target.files[0];

                    if (file) {
                        this.preview = URL.createObjectURL(file);
                    }
                }
            }
        }
    </script>
@endsection
