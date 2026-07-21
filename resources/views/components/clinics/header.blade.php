 <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
     <div>
         <h1 class="text-2xl font-bold text-gray-800">إدارة العيادات</h1>
         <p class="text-sm text-gray-500 mt-1">عرض وإدارة جميع العيادات المسجلة</p>
     </div>
     <button @click="openAdd()"
         class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-95 transition-all text-white text-sm font-medium px-5 py-2.5 rounded-xl shadow-sm">
         <i class="fas fa-plus"></i>
         إضافة عيادة
     </button>
 </div>
<x-shared.errors/>