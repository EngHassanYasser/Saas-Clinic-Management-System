<div
    class="relative w-72"
>

    <input type="hidden" name="city_id" :value="selected">

    <button
        type="button"
        @click="open = !open"
        class="w-full flex items-center justify-between rounded-lg border border-gray-300 px-4 py-2 bg-white text-right"
    >
        <span x-text="selectedName"></span>

        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div
        x-show="open"
        @click.outside="open = false"
        x-transition
        class="absolute z-20 mt-2 w-full rounded-lg border border-gray-200 bg-white shadow-lg max-h-60 overflow-y-auto"
    >
        <template x-for="city in cities" :key="city.id">
            <button
                type="button"
                @click="
                    selected = city.id;
                    selectedName = city.name;
                    open = false;
                "
                class="block w-full px-4 py-2 text-right hover:bg-blue-50"
                x-text="city.name"
            ></button>
        </template>
    </div>

</div>