   {{-- ===== STATS STRIP ===== --}}
   <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">

       <div @click="filterStatus = ''; currentPage = 1"
           class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3 cursor-pointer hover:border-blue-300 hover:shadow-sm transition-all">
           <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
               <i class="fas fa-hospital text-base"></i>
           </div>
           <div>
               <p class="text-xs text-gray-400">إجمالي العيادات</p>
               <p class="text-xl font-bold text-gray-800" x-text="clinics.length"></p>
           </div>
       </div>

       <div @click="filterStatus = 'نشط'; currentPage = 1"
           class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3 cursor-pointer hover:border-green-300 hover:shadow-sm transition-all">
           <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-500 flex-shrink-0">
               <i class="fas fa-circle-check text-base"></i>
           </div>
           <div>
               <p class="text-xs text-gray-400">نشطة</p>
               <p class="text-xl font-bold text-gray-800" x-text="clinics.filter(c => c.status === 'نشط').length"></p>
           </div>
       </div>

       <div @click="filterStatus = 'قيد المراجعة'; currentPage = 1"
           class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3 cursor-pointer hover:border-amber-300 hover:shadow-sm transition-all">
           <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500 flex-shrink-0">
               <i class="fas fa-clock text-base"></i>
           </div>
           <div>
               <p class="text-xs text-gray-400">قيد المراجعة</p>
               <p class="text-xl font-bold text-gray-800"
                   x-text="clinics.filter(c => c.status === 'قيد المراجعة').length"></p>
           </div>
       </div>

       <div @click="filterStatus = 'موقوف'; currentPage = 1"
           class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3 cursor-pointer hover:border-red-300 hover:shadow-sm transition-all">
           <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-400 flex-shrink-0">
               <i class="fas fa-ban text-base"></i>
           </div>
           <div>
               <p class="text-xs text-gray-400">موقوفة</p>
               <p class="text-xl font-bold text-gray-800" x-text="clinics.filter(c => c.status === 'موقوف').length">
               </p>
           </div>
       </div>

   </div>
