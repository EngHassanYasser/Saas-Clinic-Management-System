{{-- Service --}}
<div x-show="currencSection === serviceSection">

    <label class="block text-sm font-semibold text-gray-800 mb-2">
        الخدمه
    </label>

    <div class="relative">

        <!-- Trigger -->
        <button
            type="button"
            @click="openServices = !openServices"
            class="w-full p-3 rounded-xl border-2 border-gray-100 bg-gray-50 text-sm font-medium text-gray-700 flex items-center justify-between focus:border-teal-500 focus:ring-2 focus:ring-teal-200"
        >
            <span
                x-text="selected.service ? selected.service.name : 'اختر الخدمه'"
                :class="selected.service ? 'text-gray-700' : 'text-gray-400'"
            ></span>

            <svg
                class="w-5 h-5 transition-transform"
                :class="{ 'rotate-180': openServices }"
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
            x-show="openServices"
            x-transition
            @click.outside="openServices = false"
            class="absolute z-50 mt-2 w-full max-h-64 overflow-y-auto rounded-xl border-2 border-gray-100 bg-white shadow-lg"
        >

            <template x-for="sr in filterdServices" :key="sr.id">

                <button
                    type="button"
                    class="w-full px-4 py-3 text-right text-sm font-medium text-gray-700 hover:bg-teal-50 transition"
                    @click="
                        serviceId = sr.id;
                        selected.service = sr;
                        openServices = false;
                        onServiceChange();
                    "
                >
                    <span x-text="sr.name"></span>
                </button>

            </template>

        </div>

    </div>

</div>