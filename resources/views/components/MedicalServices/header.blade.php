<div class="mb-6">
    <div class="flex items-start justify-between gap-4">
        <!-- Title -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                خدمات العيادة
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                إدارة الخدمات والأسعار والأطباء
            </p>
        </div>
        <!-- Button -->
        <button @click="openCreate()"
            class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition whitespace-nowrap">
            + إضافة خدمة
        </button>
    </div>
    <!-- Alerts -->
   <x-shared.errors/>
</div>
