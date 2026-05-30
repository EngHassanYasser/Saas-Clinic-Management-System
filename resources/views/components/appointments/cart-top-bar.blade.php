     {{-- Card Top Bar --}}
     <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">

         <div class="flex items-center gap-2 text-gray-500 text-xs">
             <i class="fas fa-hashtag text-gray-300"></i>
             <span>حجز رقم #{{ $appt['id'] }}</span>
             <span class="text-gray-300 mx-1">|</span>
             <i class="fas fa-{{ $appt['booking_src'] === 'mobileApp' ? 'mobile-alt' : 'globe' }} text-gray-300"></i>
             <span>{{ $appt['booking_src'] === 'mobileApp' ? 'تطبيق الجوال' : 'الموقع' }}</span>
         </div>

         <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
             <i class="fas {{ $statusIcon }}"></i>
             {{ $appt['status_label'] }}
         </span>

     </div>
