
@extends('layouts-main.dashboard')

@section('content')
<div class="min-h-screen bg-slate-50 py-8"
     x-data="profileImage()">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        @if (session('success'))
            <div
                x-data="{show:true}"
                x-show="show"
                x-transition
                class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">

                <div class="flex justify-between items-center">
                    <span>{{ session('success') }}</span>

                    <button
                        type="button"
                        @click="show=false"
                        class="font-bold">
                        ×
                    </button>
                </div>
            </div>
        @endif

        <form action="{{ route('profile.update') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

                <!-- Cover -->
                <div class="h-40 bg-gradient-to-r from-cyan-600 via-teal-600 to-cyan-700">
                </div>

                <div class="px-6 md:px-10 pb-10">

                    <!-- Avatar -->
                    <div class="-mt-20 flex flex-col items-center">

                        <div class="relative">

                            <img
                                :src="preview"
                                class="w-40 h-40 rounded-full border-4 border-white object-cover shadow-xl bg-slate-100">

                            <label
                                class="absolute bottom-2 right-2 flex items-center justify-center w-12 h-12 rounded-full bg-white shadow-lg cursor-pointer hover:scale-105 transition">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5 text-slate-700"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"/>
                                </svg>

                                <input
                                    type="file"
                                    name="image"
                                    accept="image/*"
                                    class="hidden"
                                    @change="changeImage">
                            </label>

                        </div>

                        <h2 class="mt-4 text-2xl font-bold text-slate-900">
                            {{ auth()->user()->name }}
                        </h2>

                        <p class="text-slate-500">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <!-- Form Fields -->
                    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Name -->
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-700">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', auth()->user()->name) }}"
                                class="w-full rounded-xl border-slate-300 focus:border-cyan-600 focus:ring-cyan-600">

                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-700">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                class="w-full rounded-xl border-slate-300 focus:border-cyan-600 focus:ring-cyan-600">

                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-700">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', auth()->user()->phone) }}"
                                class="w-full rounded-xl border-slate-300 focus:border-cyan-600 focus:ring-cyan-600">

                            @error('phone')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-700">
                                Address
                            </label>

                            <input
                                type="text"
                                name="address"
                                value="{{ old('address', auth()->user()->address) }}"
                                class="w-full rounded-xl border-slate-300 focus:border-cyan-600 focus:ring-cyan-600">

                            @error('address')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Actions -->
                    <div class="mt-10 flex justify-end gap-3">

                        <a href="{{ url()->previous() }}"
                           class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="px-8 py-3 rounded-xl bg-cyan-600 text-white font-medium hover:bg-cyan-700 transition shadow-sm">
                            Save Changes
                        </button>

                    </div>

                </div>
            </div>

        </form>

    </div>
</div>

<script>
function profileImage() {
    return {
        preview: @js(
            auth()->user()->image
                ? asset('storage/' . auth()->user()->image)
                : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name)
        ),

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