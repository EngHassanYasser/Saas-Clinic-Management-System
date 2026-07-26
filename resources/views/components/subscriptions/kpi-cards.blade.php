<x-shared.errors/>
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
    <div class="bg-white p-5 rounded-2xl border shadow-sm hover:shadow-md transition">
        <p class="text-sm text-gray-500">إجمالي الاشتراكات</p>
        <h3 class="text-3xl font-bold mt-2 text-gray-900" x-text="stats.total"></h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border shadow-sm hover:shadow-md transition">
        <p class="text-sm text-green-600">نشطة</p>
        <h3 class="text-3xl font-bold mt-2 text-green-600" x-text="stats.active"></h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border shadow-sm hover:shadow-md transition">
        <p class="text-sm text-red-500">منتهية</p>
        <h3 class="text-3xl font-bold mt-2 text-red-500" x-text="stats.expired"></h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border shadow-sm hover:shadow-md transition">
        <p class="text-sm text-red-500">ملغيه</p>
        <h3 class="text-3xl font-bold mt-2 text-red-500" x-text="stats.cancelled"></h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border shadow-sm hover:shadow-md transition">
        <p class="text-sm text-amber-500">قريب الانتهاء</p>
        <h3 class="text-3xl font-bold mt-2 text-amber-500" x-text="stats.expiring"></h3>
    </div>
</div>
