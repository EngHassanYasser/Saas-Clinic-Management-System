<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">إدارة إعلانات العيادة</h1>
        <p class="text-gray-500 text-sm">إضافة وتعديل وحذف الإعلانات الخاصة بالعيادة</p>
    </div>
    <button @click="openAdd()"
        class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 active:scale-95 transition-all text-sm font-medium shadow-sm">
        <i class="fas fa-plus"></i>
        إضافة إعلان
    </button>
</div>
