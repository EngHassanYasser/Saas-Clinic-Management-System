 <div class="grid grid-cols-1 lg:grid-cols-1 gap-4 mb-6">
     {{-- الشكاوى الأخيرة --}}
     <div class="bg-white rounded-xl border border-gray-100 p-5">
         <div class="flex items-center justify-between mb-4">
             <h2 class="text-sm font-medium text-gray-800">الشكاوى الأخيرة</h2>
             <a href="#" class="text-xs text-teal-600 hover:underline">عرض الكل</a>
         </div>
         <div class="flex flex-col gap-3">
             @php
                 $complaints = [
                     [
                         'title' => 'تأخر موعد أكثر من ساعة',
                         'patient' => 'أحمد محمد',
                         'time' => 'منذ يومين',
                         'dot' => 'bg-red-500',
                         'badge' => 'bg-red-100 text-red-600',
                         'status' => 'عاجل',
                     ],
                     [
                         'title' => 'صعوبة في حجز موعد',
                         'patient' => 'سلمى إبراهيم',
                         'time' => 'منذ 3 أيام',
                         'dot' => 'bg-amber-400',
                         'badge' => 'bg-amber-100 text-amber-700',
                         'status' => 'قيد المراجعة',
                     ],
                     [
                         'title' => 'سوء التعامل من الاستقبال',
                         'patient' => 'محمود طارق',
                         'time' => 'منذ يوم',
                         'dot' => 'bg-red-500',
                         'badge' => 'bg-red-100 text-red-600',
                         'status' => 'عاجل',
                     ],
                     [
                         'title' => 'عدم وضوح التعليمات الطبية',
                         'patient' => 'هدى سالم',
                         'time' => 'منذ 4 أيام',
                         'dot' => 'bg-amber-400',
                         'badge' => 'bg-amber-100 text-amber-700',
                         'status' => 'قيد المراجعة',
                     ],
                 ];
             @endphp
             @foreach ($complaints as $complaint)
                 <div class="flex items-start gap-3 p-3 rounded-lg border border-gray-100">
                     <div class="w-2 h-2 rounded-full {{ $complaint['dot'] }} mt-1.5 flex-shrink-0"></div>
                     <div class="flex-1">
                         <div class="flex justify-between items-center mb-1">
                             <p class="text-sm font-medium text-gray-800">{{ $complaint['title'] }}</p>
                             <span
                                 class="text-xs px-2 py-0.5 rounded-full {{ $complaint['badge'] }}">{{ $complaint['status'] }}</span>
                         </div>
                         <p class="text-xs text-gray-400">{{ $complaint['patient'] }} — {{ $complaint['time'] }}</p>
                     </div>
                 </div>
             @endforeach
         </div>
     </div>
 </div>
