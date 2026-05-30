   {{-- ===================== DOCTORS ACTIVITY ===================== --}}
   <div class="bg-white rounded-xl border border-gray-100 p-5">
       <div class="flex items-center justify-between mb-5">
           <h2 class="text-sm font-medium text-gray-800">نشاط الأطباء هذا الشهر</h2>
       </div>
       <div class="flex flex-col gap-4">

           @php
               $doctors = [
                   ['name' => 'د. سارة أحمد', 'count' => 92, 'percent' => 92, 'color' => 'bg-teal-500'],
                   ['name' => 'د. خالد منصور', 'count' => 78, 'percent' => 78, 'color' => 'bg-blue-500'],
                   ['name' => 'د. ريم عبدالله', 'count' => 65, 'percent' => 65, 'color' => 'bg-amber-500'],
                   ['name' => 'د. محمد السيد', 'count' => 48, 'percent' => 48, 'color' => 'bg-purple-500'],
               ];
           @endphp

           @foreach ($doctors as $doctor)
               <div>
                   <div class="flex justify-between mb-1.5">
                       <span class="text-sm text-gray-700">{{ $doctor['name'] }}</span>
                       <span class="text-xs text-gray-400">{{ $doctor['count'] }} موعد</span>
                   </div>
                   <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                       <div class="{{ $doctor['color'] }} h-full rounded-full transition-all duration-700"
                           style="width: {{ $doctor['percent'] }}%"></div>
                   </div>
               </div>
           @endforeach

       </div>
   </div>
