<!-- Filters -->
<div class="bg-white p-4 rounded-xl shadow-sm mb-6 grid grid-cols-1 md:grid-cols-3 gap-3">

    <input x-model="filters.search" type="text" placeholder="ابحث باسم الخدمة..."
        class="w-full bg-gray-50 border border-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none" />

    <select x-model="filters.doctorId"
        class="w-full bg-gray-50 border border-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
        <option value="">كل الدكاترة</option>
        <template x-for="doc in clinicServices" :key="doc.doctor_id">
            <option :value="doc.doctor_id" x-text="doc.doctor_name"></option>
        </template>
    </select>

    <div class="flex items-center text-sm text-gray-500">
        عدد النتائج:
        <span class="font-semibold text-gray-900 mx-1" x-text="filteredServices.length"></span>
    </div>

</div>
