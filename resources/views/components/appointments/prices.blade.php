     {{-- Prices --}}
       <div class="flex items-center gap-3 bg-teal-50 border border-teal-100 rounded-xl px-3 py-2">
                <i class="fas fa-money-bill-wave text-teal-500 text-xs"></i>
                <div>
                    <p class="text-[11px] text-teal-600 leading-none">سعر الكشف</p>
                    <p class="text-xs font-black text-teal-700 mt-0.5">{{ $appt['service']['price'] }} جنيه</p>
                </div>
            </div>

            <div class="flex items-center gap-2 bg-amber-50 border border-amber-100 rounded-xl px-3 py-2">
                <i class="fas fa-coins text-amber-500 text-xs"></i>
                <div>
                    <p class="text-[11px] text-amber-600 leading-none">العربون المدفوع</p>
                    <p class="text-xs font-black text-amber-700 mt-0.5">{{ $appt['deposit_amount'] }} جنيه</p>
                </div>
            </div>

            <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-xl px-3 py-2">
                <i class="fas fa-receipt text-indigo-400 text-xs"></i>
                <div>
                    <p class="text-[11px] text-indigo-600 leading-none">المتبقي</p>
                    <p class="text-xs font-black text-indigo-700 mt-0.5">{{ $appt['service']['price'] - $appt['deposit_amount'] }} جنيه</p>
                </div>
            </div>