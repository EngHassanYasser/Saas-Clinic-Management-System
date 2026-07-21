   {{-- ===== STATS STRIP ===== --}}
   <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-5">

       <div @click="filterStatus = ''; currentPage = 1"
           class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3 cursor-pointer hover:border-blue-300 hover:shadow-sm transition-all">
           <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
               <i class="fas fa-hospital text-base"></i>
           </div>
           <div>
               <p class="text-xs text-gray-400">إجمالي العيادات</p>
               <p class="text-xl font-bold text-gray-800" x-text="stats.total"></p>
           </div>
       </div>

       <div
           class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3 cursor-pointer hover:border-green-300 hover:shadow-sm transition-all">
           <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-500 flex-shrink-0">
               <i class="fas fa-circle-check text-base"></i>
           </div>
           <div>
               <p class="text-xs text-gray-400">العيادات النشطه</p>
               <p class="text-xl font-bold text-gray-800" x-text="stats.active"></p>
           </div>
       </div>

       <div
           class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3 cursor-pointer hover:border-amber-300 hover:shadow-sm transition-all">
           <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500 flex-shrink-0">
               <i class="fas fa-clock text-base"></i>
           </div>
           <div>
               <p class="text-xs text-gray-400"> العيادات الغير نشطه</p>
               <p class="text-xl font-bold text-gray-800" x-text="stats.inactive"></p>
           </div>
       </div>
   </div>
