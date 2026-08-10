<div class="bg-white rounded-xl border border-gray-100 p-4 mb-6 flex flex-wrap items-center gap-3">
    <div class="relative flex-1 min-w-48">
        <i class="fa fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
        <input type="text" x-model.debounce.300ms="search" placeholder="ابحث باسم الطبيب..."
            class="w-full border border-gray-200 rounded-lg pr-9 pl-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
    </div>
    <select x-model="status"
        class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:border-teal-400 bg-white min-w-36">
        <option value="">
            كل الحالات
        </option>
        <option value="active">
            جارية
        </option>
        <option value="upcoming">
            قادمة
        </option>
        <option value="ended">
            منتهية
        </option>
    </select>
    <input type="month" x-model="month"
        class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:border-teal-400 bg-white">
</div>
