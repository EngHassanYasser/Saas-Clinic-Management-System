<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2 text-gray-700 font-semibold text-sm">
            <i class="fas fa-bolt text-amber-400"></i>
            آخر النشاطات
        </div>
        <a href="#" class="text-xs text-blue-500 hover:underline flex items-center gap-1">
            عرض الكل
            <i class="fas fa-arrow-left text-[10px]"></i>
        </a>
    </div>
    <div class="divide-y divide-gray-50">
        <template x-for="lastActivity in lastActivities">
            <div class="flex items-center justify-between py-3 gap-3">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 text-sm flex-shrink-0">
                        <i class="fas fa-plus"></i>
                    </div>
                    <p class="text-sm text-gray-700" x-text="lastActivity.title"></p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs bg-blue-50 text-blue-600 font-medium px-2.5 py-1 rounded-full"
                        x-text="lastActivity.status"></span>
                    <span class="text-xs text-gray-400" x-text="lastActivity.created_at"></span>
                </div>
            </div>
        </template>
    </div>
    <x-shared.pagination />
</div>
