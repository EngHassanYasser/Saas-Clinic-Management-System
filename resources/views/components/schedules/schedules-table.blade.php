 {{-- SCHEDULES TABLE --}}
 <div x-show="open" x-transition x-cloak class="border-t border-gray-100">

     @if ($doctor['schedules_count'] == 0)
         <div class="py-8 text-center text-sm text-gray-400">
             <i class="fa fa-calendar-xmark text-2xl mb-2 block text-gray-200"></i>
             لا توجد مواعيد مضافة بعد
         </div>
     @else
         <table class="w-full text-sm">
             <thead>
                 <tr class="bg-gray-50 text-xs text-gray-500">
                     <th class="text-right px-4 py-2.5 font-medium">الأيام</th>
                     <th class="text-right px-4 py-2.5 font-medium">من</th>
                     <th class="text-right px-4 py-2.5 font-medium">إلى</th>
                     <th class="text-right px-4 py-2.5 font-medium">بريك</th>
                     <th class="text-right px-4 py-2.5 font-medium">مدة الكشف</th>
                     <th class="px-4 py-2.5"></th>
                 </tr>
             </thead>
             <tbody class="divide-y divide-gray-50">
                 @foreach ($doctor['schedules'] as $schedule)
                     <tr class="hover:bg-gray-50 transition">
                         <td class="px-4 py-3">
                             <div class="flex flex-wrap gap-1">
                                 @foreach ($schedule->days as $day)
                                     <span
                                         class="text-xs bg-teal-50 text-teal-700 border border-teal-100 px-2 py-0.5 rounded-md">
                                         {{ $day->name }}
                                     </span>
                                 @endforeach
                             </div>
                         </td>
                         <td class="px-4 py-3 text-gray-700">{{ $schedule['start_time'] }}</td>
                         <td class="px-4 py-3 text-gray-700">{{ $schedule['end_time'] }}</td>
                         <td class="px-4 py-3 text-gray-500 text-xs">
                             @if ($schedule['start_break'] && $schedule['end_break'])
                                 {{ $schedule['start_break'] }} — {{ $schedule['end_break'] }}
                             @else
                                 <span class="text-gray-300">—</span>
                             @endif
                         </td>
                         <td class="px-4 py-3 text-gray-700">{{ $schedule['slot_duration'] }} د</td>
                         <td class="px-4 py-3">
                             <div class="flex items-center gap-2 justify-end">
                                 <button type="button" @click.stop="openEdit(@js($schedule))"
                                     class="text-xs text-blue-500 hover:text-blue-700 transition">
                                     <i class="fa fa-pen"></i>
                                 </button>
                                 <form action="" method="POST" onsubmit="return confirm('تأكيد الحذف؟')">
                                     @csrf @method('DELETE')
                                     <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition">
                                         <i class="fa fa-trash"></i>
                                     </button>
                                 </form>
                             </div>
                         </td>
                     </tr>
                 @endforeach
             </tbody>
         </table>
     @endif
 </div>
