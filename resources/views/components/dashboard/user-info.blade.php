 <div class="sb-user">
     <div class="p-4 flex items-center gap-3 justify-start border-b border-white/10">
         <div
             class="w-11 h-11 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
             {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
         </div>
         <div class="text-right overflow-hidden">
             <p class="text-white font-semibold text-sm truncate">
                 {{ auth()->user()->name ?? 'المستخدم' }}
             </p>
             @if (auth()->user()->type == 'clinic')
                 <p class="text-teal-200 text-xs">{{ auth()->user()->clinic_name ?? 'العيادة' }}</p>
             @endif
         </div>

     </div>
 </div>
