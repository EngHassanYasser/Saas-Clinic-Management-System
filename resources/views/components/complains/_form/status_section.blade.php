 {{-- Status section --}}
 @if (auth()->user()->type === 'clinic')
     <div>
         <p class="text-xs text-gray-400 mb-2">تغيير الحالة</p>
         <div class="flex gap-2">
             <button onclick="changeStatus('pending')"
                 class="flex-1 text-xs py-2 rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 transition">في
                 الانتظار</button>
             <button onclick="changeStatus('reviewing')"
                 class="flex-1 text-xs py-2 rounded-lg border border-amber-200 text-amber-600 hover:bg-amber-50 transition">قيد
                 المراجعة</button>
             <button onclick="changeStatus('resolved')"
                 class="flex-1 text-xs py-2 rounded-lg border border-emerald-200 text-emerald-600 hover:bg-emerald-50 transition">تم
                 الحل</button>
         </div>
     </div>
 @else
     <div>
         <p class="text-xs text-gray-400 mb-2">الحالة</p>
         <div id="readonlyStatus"
             class="text-xs py-2 px-3 rounded-lg border border-emerald-200 text-emerald-600 bg-emerald-50 inline-block">
         </div>
     </div>
 @endif
