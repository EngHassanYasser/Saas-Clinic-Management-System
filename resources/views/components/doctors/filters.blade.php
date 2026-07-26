<div class="bg-white rounded-xl border border-gray-100 p-4 mb-6 flex flex-wrap items-center gap-3">
    {{-- Search --}}
    <div class="relative flex-1 min-w-48">
        <i class="fa fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
        <input type="text"
            x-model="search"
            placeholder="ابحث عن طبيب..."
            class="w-full border border-gray-200 rounded-lg pr-9 pl-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
    </div>
    {{-- Specialty --}}
    <select x-model="specialty"
        class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:border-teal-400 bg-white min-w-36">
        <option value="">كل التخصصات</option>
        <option value="طب عام">طب عام</option>
        <option value="قلب">قلب وأوعية دموية</option>
        <option value="عظام">عظام</option>
        <option value="جلدية">جلدية</option>
        <option value="أطفال">أطفال</option>
        <option value="أعصاب">مخ وأعصاب</option>
        <option value="عيون">عيون</option>
    </select>
    {{-- Status --}}
    <select x-model="status"
        class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:border-teal-400 bg-white min-w-32">
        <option value="">كل الحالات</option>
        <option value="active">متاح</option>
        <option value="inactive">غير متاح</option>
    </select>
</div>