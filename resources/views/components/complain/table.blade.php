 {{-- ===================== TABLE ===================== --}}
 <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
     <table class="w-full text-sm">
         <thead>
             <tr class="border-b border-gray-100 text-xs text-gray-400 bg-gray-50">
                 <th class="text-right px-4 py-3 font-medium">المريض</th>
                 <th class="text-right px-4 py-3 font-medium">الموضوع</th>
                 <th class="text-right px-4 py-3 font-medium">ضد</th>
                 <th class="text-right px-4 py-3 font-medium">الأولوية</th>
                 <th class="text-right px-4 py-3 font-medium">الحالة</th>
                 <th class="text-right px-4 py-3 font-medium">التاريخ</th>
                 <th class="px-4 py-3"></th>
             </tr>
         </thead>
         <tbody class="divide-y divide-gray-50" id="tableBody">

             @php
                 $complaints = [
                     [
                         'patient' => 'أحمد محمد',
                         'initials' => 'أم',
                         'color' => 'bg-blue-100 text-blue-600',
                         'subject' => 'تأخر موعد أكثر من ساعة',
                         'against' => 'د. سارة أحمد',
                         'priority' => 'urgent',
                         'status' => 'reviewing',
                         'date' => '2026-05-28',
                     ],
                     [
                         'patient' => 'سلمى إبراهيم',
                         'initials' => 'سإ',
                         'color' => 'bg-purple-100 text-purple-600',
                         'subject' => 'صعوبة في حجز موعد',
                         'against' => 'الاستقبال',
                         'priority' => 'normal',
                         'status' => 'pending',
                         'date' => '2026-05-27',
                     ],
                     [
                         'patient' => 'محمود طارق',
                         'initials' => 'مط',
                         'color' => 'bg-teal-100 text-teal-600',
                         'subject' => 'سوء التعامل من الاستقبال',
                         'against' => 'الاستقبال',
                         'priority' => 'urgent',
                         'status' => 'reviewing',
                         'date' => '2026-05-29',
                     ],
                     [
                         'patient' => 'هدى سالم',
                         'initials' => 'هس',
                         'color' => 'bg-amber-100 text-amber-600',
                         'subject' => 'عدم وضوح التعليمات الطبية',
                         'against' => 'د. خالد منصور',
                         'priority' => 'normal',
                         'status' => 'pending',
                         'date' => '2026-05-26',
                     ],
                     [
                         'patient' => 'كريم عادل',
                         'initials' => 'كع',
                         'color' => 'bg-rose-100 text-rose-600',
                         'subject' => 'خطأ في وصف الدواء',
                         'against' => 'د. ريم عبدالله',
                         'priority' => 'urgent',
                         'status' => 'reviewing',
                         'date' => '2026-05-25',
                     ],
                     [
                         'patient' => 'نور الدين',
                         'initials' => 'نل',
                         'color' => 'bg-green-100 text-green-600',
                         'subject' => 'تأخر في نتائج التحاليل',
                         'against' => 'المعمل',
                         'priority' => 'normal',
                         'status' => 'resolved',
                         'date' => '2026-05-20',
                     ],
                     [
                         'patient' => 'فاطمة علي',
                         'initials' => 'فع',
                         'color' => 'bg-pink-100 text-pink-600',
                         'subject' => 'إلغاء الموعد بدون إشعار',
                         'against' => 'د. سارة أحمد',
                         'priority' => 'normal',
                         'status' => 'resolved',
                         'date' => '2026-05-18',
                     ],
                     [
                         'patient' => 'يوسف حسن',
                         'initials' => 'يح',
                         'color' => 'bg-indigo-100 text-indigo-600',
                         'subject' => 'ارتفاع سعر الكشف بدون إشعار',
                         'against' => 'الإدارة',
                         'priority' => 'normal',
                         'status' => 'resolved',
                         'date' => '2026-05-15',
                     ],
                 ];

                 $statusMap = [
                     'pending' => ['label' => 'في الانتظار', 'class' => 'bg-blue-100 text-blue-700'],
                     'reviewing' => ['label' => 'قيد المراجعة', 'class' => 'bg-amber-100 text-amber-700'],
                     'resolved' => ['label' => 'تم الحل', 'class' => 'bg-emerald-100 text-emerald-700'],
                 ];

                 $priorityMap = [
                     'urgent' => ['label' => 'عاجل', 'class' => 'bg-red-100 text-red-600'],
                     'normal' => ['label' => 'عادي', 'class' => 'bg-gray-100 text-gray-500'],
                 ];
             @endphp

             @foreach ($complaints as $c)
                 @php
                     $st = $statusMap[$c['status']];
                     $pr = $priorityMap[$c['priority']];
                 @endphp
                 <tr class="complaint-row hover:bg-gray-50 transition cursor-pointer" data-patient="{{ $c['patient'] }}"
                     data-subject="{{ $c['subject'] }}" data-status="{{ $c['status'] }}"
                     data-priority="{{ $c['priority'] }}" onclick="openDetails(this)">
                     <td class="px-4 py-3">
                         <div class="flex items-center gap-2.5">
                             <div
                                 class="w-8 h-8 rounded-lg {{ $c['color'] }} flex items-center justify-center text-xs font-medium flex-shrink-0">
                                 {{ $c['initials'] }}
                             </div>
                             <span class="font-medium text-gray-700">{{ $c['patient'] }}</span>
                         </div>
                     </td>
                     <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $c['subject'] }}</td>
                     <td class="px-4 py-3 text-gray-500 text-xs">{{ $c['against'] }}</td>
                     <td class="px-4 py-3">
                         <span class="text-xs px-2.5 py-1 rounded-full {{ $pr['class'] }}">{{ $pr['label'] }}</span>
                     </td>
                     <td class="px-4 py-3">
                         <span class="text-xs px-2.5 py-1 rounded-full {{ $st['class'] }}">{{ $st['label'] }}</span>
                     </td>
                     <td class="px-4 py-3 text-gray-400 text-xs">
                         {{ \Carbon\Carbon::parse($c['date'])->format('d/m/Y') }}
                     </td>
                     <td class="px-4 py-3" onclick="event.stopPropagation()">
                         <div class="flex items-center gap-3">
                             <button onclick="openDetails(this.closest('tr'))"
                                 class="text-teal-500 hover:text-teal-700 transition">
                                 <i class="fa fa-eye text-xs"></i>
                             </button>
                             <button onclick="confirmDelete(this)" class="text-red-400 hover:text-red-600 transition">
                                 <i class="fa fa-trash text-xs"></i>
                             </button>
                         </div>
                     </td>
                 </tr>
             @endforeach

         </tbody>
     </table>

     {{-- Empty State --}}
     <div id="emptyState" class="hidden flex-col items-center justify-center py-16 text-gray-300">
         <i class="fa fa-flag text-5xl mb-4"></i>
         <p class="text-sm">لا توجد شكاوى مطابقة</p>
     </div>

 </div>
