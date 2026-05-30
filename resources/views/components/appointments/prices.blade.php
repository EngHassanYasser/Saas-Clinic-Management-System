     {{-- Service + Prices + Actions --}}
     <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

         {{-- Service & Prices --}}
         <div class="flex flex-wrap items-center gap-3">

             <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2">
                 <i class="fas fa-notes-medical text-gray-400 text-xs"></i>
                 <div>
                     <p class="text-xs text-gray-400 leading-none">الخدمة</p>
                     <p class="text-xs font-bold text-gray-700 mt-0.5">
                         {{ $appt['service'] }}</p>
                 </div>
             </div>

             <div class="flex items-center gap-2 bg-teal-50 border border-teal-100 rounded-xl px-3 py-2">
                 <i class="fas fa-money-bill-wave text-teal-500 text-xs"></i>
                 <div>
                     <p class="text-xs text-teal-600 leading-none">سعر الكشف</p>
                     <p class="text-xs font-black text-teal-700 mt-0.5">
                         {{ $appt['exam_price'] }} جنيه</p>
                 </div>
             </div>

             <div class="flex items-center gap-2 bg-amber-50 border border-amber-100 rounded-xl px-3 py-2">
                 <i class="fas fa-coins text-amber-500 text-xs"></i>
                 <div>
                     <p class="text-xs text-amber-600 leading-none">العربون المدفوع</p>
                     <p class="text-xs font-black text-amber-700 mt-0.5">
                         {{ $appt['deposit'] }} جنيه</p>
                 </div>
             </div>

             <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-xl px-3 py-2">
                 <i class="fas fa-receipt text-indigo-400 text-xs"></i>
                 <div>
                     <p class="text-xs text-indigo-600 leading-none">المتبقي</p>
                     <p class="text-xs font-black text-indigo-700 mt-0.5">
                         {{ $appt['exam_price'] - $appt['deposit'] }} جنيه</p>
                 </div>
             </div>

         </div>
     </div>
