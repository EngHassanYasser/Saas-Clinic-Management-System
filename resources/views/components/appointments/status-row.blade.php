 <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

     <div class="c-stat-teal rounded-2xl p-4 sm:p-5 text-white shadow-lg">
         <div class="flex items-start justify-between">
             <div>
                 <p class="text-teal-100 text-xs sm:text-sm">إجمالي المواعيد</p>
                 <p class="text-2xl sm:text-3xl font-black mt-1" x-text="stats.total ?? 'N/A'">
                 </p>
             </div>
             <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                 <i class="fas fa-calendar-alt text-sm"></i>
             </div>
         </div>
     </div>

     <div class="c-stat-amber rounded-2xl p-4 sm:p-5 text-white shadow-lg">
         <div class="flex items-start justify-between">
             <div>
                 <p class="text-amber-100 text-xs sm:text-sm">قادمة</p>
                 <p class="text-2xl sm:text-3xl font-black mt-1" x-text="stats.confirmed ?? 'N/A'"></p>
             </div>
             <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                 <i class="fas fa-clock text-sm"></i>
             </div>
         </div>
     </div>

     <div class="c-stat-blue rounded-2xl p-4 sm:p-5 text-white shadow-lg">
         <div class="flex items-start justify-between">
             <div>
                 <p class="text-blue-100 text-xs sm:text-sm">مكتملة</p>
                 <p class="text-2xl sm:text-3xl font-black mt-1" x-text="stats.completed ?? 'N/A'"></p>
             </div>
             <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                 <i class="fas fa-check-circle text-sm"></i>
             </div>
         </div>
     </div>

     <div class="c-stat-rose rounded-2xl p-4 sm:p-5 text-white shadow-lg">
         <div class="flex items-start justify-between">
             <div>
                 <p class="text-rose-100 text-xs sm:text-sm">ملغية</p>
                 <p class="text-2xl sm:text-3xl font-black mt-1" x-text="stats.cancelled ?? 'N/A'"></p>
             </div>
             <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                 <i class="fas fa-times-circle text-sm"></i>
             </div>
         </div>
     </div>

 </div>
