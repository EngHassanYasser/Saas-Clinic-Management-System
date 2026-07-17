    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-teal-50 flex items-center justify-center flex-shrink-0">
                <i class="fa fa-user-md text-teal-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">إجمالي الأطباء</p>
                <p class="text-xl font-medium text-gray-800" x-text="stats.total"></p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <i class="fa fa-circle-check text-emerald-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">متاح الآن</p>
                <p class="text-xl font-medium text-gray-800" x-text="stats.active"></p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                <i class="fa fa-circle-pause text-amber-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">غير متاح</p>
                <p class="text-xl font-medium text-gray-800" x-text="stats.inactive"></p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                <i class="fa fa-stethoscope text-blue-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">التخصصات</p>
                <p class="text-xl font-medium text-gray-800" x-text="stats.specialities"></p>
            </div>
        </div>
    </div>
