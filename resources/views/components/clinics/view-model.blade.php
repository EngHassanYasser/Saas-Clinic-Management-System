 {{-- ===== VIEW MODAL ===== --}}
 <div x-show="showView" x-cloak @click.self="showView = false" @keydown.escape.window="showView = false"
     x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

     <div x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">

         <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
             <h2 class="text-base font-bold text-gray-800">تفاصيل العيادة</h2>
             <button @click="showView = false"
                 class="w-8 h-8 rounded-lg hover:bg-gray-100 text-gray-400 flex items-center justify-center transition">
                 <i class="fas fa-xmark"></i>
             </button>
         </div>

         <template x-if="viewTarget">
             <div class="p-6 space-y-4">

                 <div class="flex items-center gap-4">
                     <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-xl flex-shrink-0"
                         :class="avatarClass(viewTarget.name)" x-text="avatarLetter(viewTarget.name)"></div>
                     <div>
                         <p class="text-lg font-bold text-gray-800" x-text="viewTarget.name"></p>
                         <p class="text-sm text-gray-400" x-text="viewTarget.email"></p>
                     </div>
                 </div>

                 <div class="grid grid-cols-2 gap-3 pt-2">

                     <div class="bg-gray-50 rounded-xl p-3">
                         <p class="text-xs text-gray-400 mb-1"><i class="fas fa-location-dot ml-1"></i>المدينة</p>
                         <p class="text-sm font-semibold text-gray-700" x-text="viewTarget.city"></p>
                     </div>

                     <div class="bg-gray-50 rounded-xl p-3">
                         <p class="text-xs text-gray-400 mb-1"><i class="fas fa-circle-half-stroke ml-1"></i>الحالة
                         </p>
                         <p class="text-sm font-semibold" :class="statusTextClass(viewTarget.status)"
                             x-text="viewTarget.status"></p>
                     </div>

                     <div class="bg-gray-50 rounded-xl p-3">
                         <p class="text-xs text-gray-400 mb-1"><i class="fas fa-crown ml-1"></i>الباقة</p>
                         <p class="text-sm font-semibold text-gray-700" x-text="viewTarget.plan"></p>
                     </div>

                     <div class="bg-gray-50 rounded-xl p-3">
                         <p class="text-xs text-gray-400 mb-1"><i class="fas fa-calendar ml-1"></i>تاريخ الانضمام
                         </p>
                         <p class="text-sm font-semibold text-gray-700" x-text="viewTarget.date"></p>
                     </div>

                 </div>
             </div>
         </template>

         <div class="flex gap-3 px-6 pb-6">
             <button @click="showView = false; openEdit(viewTarget)"
                 class="flex-1 bg-amber-500 hover:bg-amber-600 active:scale-95 text-white text-sm font-medium py-2.5 rounded-xl transition-all">
                 <i class="fas fa-pen ml-1.5"></i> تعديل
             </button>
             <button @click="showView = false"
                 class="flex-1 border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium py-2.5 rounded-xl transition">
                 إغلاق
             </button>
         </div>

     </div>
 </div>
