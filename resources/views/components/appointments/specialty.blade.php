<div  x-show="currencSection === specialitySection" class="relative">
    <label class="block text-sm font-semibold text-gray-800 mb-2">
        التخصص
    </label>
    <!-- Trigger -->
    <button
        type="button"
        @click="openSpecialties = !openSpecialties"
        class="w-full p-3 rounded-xl border-2 border-gray-100 bg-gray-50 text-sm font-medium text-gray-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition flex items-center justify-between"
    >
        <span
            x-text="selected.specialty ? selected.specialty.name : 'اختر التخصص'"
            :class="selected.specialty ? 'text-gray-700' : 'text-gray-400'"
        ></span>
        <svg
            class="w-5 h-5 transition-transform duration-200"
            :class="{ 'rotate-180': openSpecialties }"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <!-- Dropdown -->
    <div
        x-show="openSpecialties"
        x-transition
        @click.outside="openSpecialties = false"
        class="absolute z-50 mt-2 w-full rounded-xl border-2 border-gray-100 bg-white shadow-lg overflow-hidden"
    >
        <template x-for="sp in specialties" :key="sp.id">
            <button
                type="button"
                class="w-full px-4 py-3 text-right text-sm font-medium text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition"
                @click="
                    specialtyId = sp.id;
                    selected.specialty = sp;
                    openSpecialties = false;
                    onSpecialtyChange();
                "
            >
                <span x-text="sp.name"></span>
            </button>
        </template>
    </div>
</div>