<div class="-mt-20 flex flex-col items-center">
    <div class="relative">
        <img :src="preview"
            class="w-40 h-40 rounded-full border-4 border-white object-cover shadow-xl bg-slate-100">
        <label
            class="absolute bottom-2 right-2 flex items-center justify-center w-12 h-12 rounded-full bg-white shadow-lg cursor-pointer hover:scale-105 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z" />
            </svg>
            <input type="file" name="image" accept="image/*" class="hidden" @change="changeImage">
        </label>
    </div>
    <h2 class="mt-4 text-2xl font-bold text-slate-900">
        {{ auth()->user()->name }}
    </h2>
    <p class="text-slate-500">
        {{ auth()->user()->email }}
    </p>
</div>
