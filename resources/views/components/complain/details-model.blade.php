 {{-- ===================== DETAILS MODAL ===================== --}}
 <div id="detailsModal" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center" dir="rtl">
     <div class="bg-white rounded-2xl w-full max-w-lg mx-4 shadow-xl overflow-hidden">

         {{-- Header --}}
         <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
             <h3 class="text-base font-medium text-gray-800">تفاصيل الشكوى</h3>
             <button onclick="closeDetails()" class="text-gray-400 hover:text-gray-600 transition">
                 <i class="fa fa-xmark"></i>
             </button>
         </div>

         {{-- Body --}}
         <div class="p-6 flex flex-col gap-4">

             <div class="flex items-start gap-3">
                 <div id="detailsAvatar"
                     class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-medium flex-shrink-0">
                 </div>
                 <div>
                     <p id="detailsPatient" class="font-medium text-gray-800 text-sm"></p>
                     <p id="detailsDate" class="text-xs text-gray-400 mt-0.5"></p>
                 </div>
                 <div class="mr-auto flex gap-2">
                     <span id="detailsPriority" class="text-xs px-2.5 py-1 rounded-full"></span>
                     <span id="detailsStatus" class="text-xs px-2.5 py-1 rounded-full"></span>
                 </div>
             </div>

             <div class="bg-gray-50 rounded-xl p-4">
                 <p class="text-xs text-gray-400 mb-1">الموضوع</p>
                 <p id="detailsSubject" class="text-sm text-gray-700 font-medium"></p>
             </div>

             <div class="bg-gray-50 rounded-xl p-4">
                 <p class="text-xs text-gray-400 mb-1">الشكوى ضد</p>
                 <p id="detailsAgainst" class="text-sm text-gray-700"></p>
             </div>
             @if (auth()->user()->type == 'clinic')
                 {{-- Change Status --}}
                 <div>
                     <p class="text-xs text-gray-400 mb-2">تغيير الحالة</p>
                     <div class="flex gap-2">
                         <button onclick="changeStatus('pending')"
                             class="flex-1 text-xs py-2 rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 transition">
                             في الانتظار
                         </button>
                         <button onclick="changeStatus('reviewing')"
                             class="flex-1 text-xs py-2 rounded-lg border border-amber-200 text-amber-600 hover:bg-amber-50 transition">
                             قيد المراجعة
                         </button>
                         <button onclick="changeStatus('resolved')"
                             class="flex-1 text-xs py-2 rounded-lg border border-emerald-200 text-emerald-600 hover:bg-emerald-50 transition">
                             تم الحل
                         </button>
                     </div>
                 </div>
             @else
                 <div>
                     <p class="text-xs text-gray-400 mb-2">الحالة</p>
                     <div class="flex">
                         <div
                             class="gap-2 flex-1 text-xs py-2 px-2 rounded-lg border border-emerald-200 text-emerald-600 hover:bg-emerald-50 transition">
                             تم الحل
                             </button>
                         </div>
                     </div>
                 </div>
             @endif
             @if (auth()->user()->type == 'clinic')
                 {{-- Reply --}}
                 <div>
                     <p class="text-xs text-gray-400 mb-1.5">الرد على الشكوى</p>
                     <textarea id="replyText" rows="3"
                         class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition resize-none"
                         placeholder="اكتب ردك هنا..."></textarea>
                 </div>
             @else
                 <div>
                     <p class="text-xs text-gray-400 mb-1.5">الرد على الشكوى</p>
                     <textarea readonly id="replyText" rows="3"
                         class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800  focus:outline-none transition resize-none">
                         </textarea>
                 </div>
             @endif

         </div>

         <div class="flex gap-3 px-6 pb-6">
             <button onclick="closeDetails()"
                 class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">
                 إغلاق
             </button>
             @if (auth()->user()->type == 'clinic')
                 <button onclick="sendReply()"
                     class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                     <i class="fa fa-paper-plane"></i> إرسال الرد
                 </button>
             @endif
         </div>

     </div>
 </div>
