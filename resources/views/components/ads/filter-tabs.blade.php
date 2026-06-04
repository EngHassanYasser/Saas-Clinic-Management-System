    {{-- ===== FILTER TABS ===== --}}
    <div class="flex gap-2 mb-5">

        <button @click="activeFilter = 'all'"
            :class="activeFilter === 'all' ? 'bg-gray-800 text-white' :
                'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
            class="px-4 py-1.5 rounded-lg text-sm font-medium transition">
            الكل
            <span class="mr-1 text-xs opacity-70" x-text="'(' + ads.length + ')'"></span>
        </button>

        <button @click="activeFilter = 'active'"
            :class="activeFilter === 'active' ? 'bg-green-600 text-white' :
                'bg-green-50 text-green-700 border border-green-100 hover:bg-green-100'"
            class="px-4 py-1.5 rounded-lg text-sm font-medium transition">
            نشط
            <span class="mr-1 text-xs opacity-70"
                x-text="'(' + ads.filter(a => a.status === 'active').length + ')'"></span>
        </button>

        <button @click="activeFilter = 'inactive'"
            :class="activeFilter === 'inactive' ? 'bg-red-500 text-white' :
                'bg-red-50 text-red-600 border border-red-100 hover:bg-red-100'"
            class="px-4 py-1.5 rounded-lg text-sm font-medium transition">
            غير نشط
            <span class="mr-1 text-xs opacity-70"
                x-text="'(' + ads.filter(a => a.status === 'inactive').length + ')'"></span>
        </button>

    </div>
