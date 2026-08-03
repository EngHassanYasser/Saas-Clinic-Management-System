<div>
    <label class="block mb-2 text-sm font-semibold text-slate-700">
        City
    </label>

    <div class="relative">
        <button type="button" x-cloack @click="open = !open"
            class="w-full flex items-center justify-between rounded-xl border border-slate-300 bg-white px-3 py-2 shadow-sm
                   focus:border-cyan-600 focus:ring-2 focus:ring-cyan-200">
            <span x-text="selected.name || 'Select City'" class="text-slate-700"></span>

            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <input  type="hidden" name="city" :value="selected">

        <div x-show="open" @click.outside="open = false" x-transition
            class="absolute z-50 mt-2 w-full rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
            <template x-for="city in cities" :key="city.id">
                <button type="button"
                    @click="
                        selected=city
                        open=false;
                    "
                    x-text="city.name" class="w-full px-4 py-2 text-left hover:bg-cyan-50 hover:text-cyan-700">

                </button>
            </template>
        </div>
    </div>

    @error('city')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
