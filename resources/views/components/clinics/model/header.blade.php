 <div class="flex items-center justify-between px-2 border-b border-gray-100">
     <h2 class="text-base font-bold text-gray-800" x-text="mode =='update' ? 'تعديل بيانات العيادة' : 'إضافة عيادة جديدة'"></h2>
     <button @click="showModal = false"
         class="w-8 h-8 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 flex items-center justify-center transition">
         <i class="fas fa-xmark"></i>
     </button>
 </div>
