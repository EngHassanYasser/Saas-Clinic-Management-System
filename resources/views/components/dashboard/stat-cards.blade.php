 {{-- ===================== STAT CARDS ===================== --}}
 <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

     {{-- المرضى --}}
     <div
         class="bg-white rounded-xl border border-gray-100 p-5 flex items-center gap-4 transition hover:-translate-y-1 duration-200">
         <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
             <i class="fa fa-users text-blue-600 text-xl"></i>
         </div>
         <div>
             <p class="text-xs text-gray-500 mb-1">إجمالي المرضى</p>
             <p class="text-2xl font-medium text-gray-800 leading-none">1,284</p>
             <p class="text-xs text-emerald-600 mt-1"><i class="fa fa-arrow-up"></i> +12% هذا الشهر</p>
         </div>
     </div>

     {{-- الأطباء --}}
     <div
         class="bg-white rounded-xl border border-gray-100 p-5 flex items-center gap-4 transition hover:-translate-y-1 duration-200">
         <div class="w-12 h-12 rounded-lg bg-teal-50 flex items-center justify-center flex-shrink-0">
             <i class="fa fa-user-md text-teal-600 text-xl"></i>
         </div>
         <div>
             <p class="text-xs text-gray-500 mb-1">الأطباء</p>
             <p class="text-2xl font-medium text-gray-800 leading-none">18</p>
             <p class="text-xs text-gray-400 mt-1">15 متاح الآن</p>
         </div>
     </div>

     {{-- المواعيد --}}
     <div
         class="bg-white rounded-xl border border-gray-100 p-5 flex items-center gap-4 transition hover:-translate-y-1 duration-200">
         <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
             <i class="fa fa-calendar-check text-amber-600 text-xl"></i>
         </div>
         <div>
             <p class="text-xs text-gray-500 mb-1">مواعيد هذا الشهر</p>
             <p class="text-2xl font-medium text-gray-800 leading-none">347</p>
             <p class="text-xs text-emerald-600 mt-1"><i class="fa fa-arrow-up"></i> +8% عن الشهر الماضي</p>
         </div>
     </div>

     {{-- الشكاوى --}}
     <div
         class="bg-white rounded-xl border border-gray-100 p-5 flex items-center gap-4 transition hover:-translate-y-1 duration-200">
         <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
             <i class="fa fa-exclamation-circle text-red-500 text-xl"></i>
         </div>
         <div>
             <p class="text-xs text-gray-500 mb-1">شكاوى مفتوحة</p>
             <p class="text-2xl font-medium text-gray-800 leading-none">7</p>
             <p class="text-xs text-red-500 mt-1">3 تحتاج رد عاجل</p>
         </div>
     </div>

 </div>
