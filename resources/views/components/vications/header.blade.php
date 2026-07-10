 {{-- ===================== HEADER ===================== --}}
 <div class="flex items-center justify-between mb-6">

     <div>

         <div class="flex items-center gap-2 text-sm text-gray-400 mb-1">
             <a href="" class="hover:text-teal-600">الرئيسية</a>

             <i class="fa fa-chevron-left text-xs"></i>

             <a href="" class="hover:text-teal-600">
                 الأطباء
             </a>

             <i class="fa fa-chevron-left text-xs"></i>

             <span class="text-gray-600">
                 الإجازات
             </span>
         </div>

         <h1 class="text-xl font-medium text-gray-800">
             إجازات الأطباء
         </h1>

     </div>

     <button @click="openModal()"
         class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">

         <i class="fa fa-umbrella-beach"></i>

         إضافة إجازة

     </button>

 </div>
