 {{-- الحالة --}}
 <div class="bg-white rounded-xl border border-gray-100 p-5">
     <h2 class="text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
         <i class="fa fa-toggle-on text-teal-500"></i> الحالة
     </h2>
     <label class="flex items-center justify-between cursor-pointer">
         <span class="text-sm text-gray-600">الطبيب متاح للحجز</span>
         <div class="relative" @click="isActive = !isActive">
             <input type="checkbox" name="is_active" x-model="isActive" class="sr-only">
             <div class="w-11 h-6 rounded-full transition-colors duration-200"
                 :class="isActive ? 'bg-teal-500' : 'bg-gray-300'"></div>
             <div class="absolute top-0.5 right-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200"
                 :style="isActive ? '' : 'transform: translateX(20px)'"></div>
         </div>
     </label>
 </div>
