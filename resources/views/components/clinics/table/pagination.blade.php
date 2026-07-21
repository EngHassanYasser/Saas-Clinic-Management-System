<div class="mt-6 flex items-center justify-center gap-2">

    <template x-for="link in pagination" :key="link.label">

        <template x-if="link.url">
            <a
                :href="link.url"
                x-html="link.label"
                class="flex h-10 min-w-10 items-center justify-center rounded-lg border px-3"
                :class="link.active
                    ? 'bg-blue-600 text-white border-blue-600'
                    : 'bg-white hover:bg-gray-100'"
            ></a>
        </template>

        <template x-if="!link.url">
            <span
                x-html="link.label"
                class="flex h-10 min-w-10 items-center justify-center rounded-lg border px-3 text-gray-400"
            ></span>
        </template>

    </template>

</div>