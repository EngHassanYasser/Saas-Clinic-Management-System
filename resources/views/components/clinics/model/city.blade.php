<div class="relative col-span-2" x-data="{ open: false, selected: null }" class="relative">
    <input type="hidden" x-model="form.city.id" name="cityId">
    <label class="block text-xs font-semibold text-gray-500 mb-1.5">
        المدينة
        <span class="text-red-400">*</span>
    </label>
    <!-- Select -->
    <button type="button" @click="open = !open"
        class="w-full flex items-center justify-between border border-gray-200 bg-gray-50 px-3 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-blue-300">
        <span x-text="form.city.id? form.city.name : 'اختر المدينة'"
            :class="form.city.id ? 'text-gray-900' : 'text-gray-400'"></span>

        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <!-- Dropdown -->
    <div x-show="open" @click.outside="open = false" x-transition
        class="absolute z-50 mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
        <template x-for="city in cities" :key="city.id">
            <button type="button"
                @click="
                    open = false;
                    form.city=city;
                "
                class="w-full text-right px-4 py-2 text-sm hover:bg-blue-50"
                :class="form.city?.id === city.id ? 'bg-blue-100 text-blue-700 font-semibold' : ''">
                <span x-text="city.name"></span>
            </button>
        </template>
    </div>
</div>

