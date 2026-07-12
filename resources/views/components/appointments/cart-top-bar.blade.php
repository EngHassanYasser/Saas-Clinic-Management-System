     {{-- Card Top Bar --}}
     <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">

         <div class="flex items-center gap-2 text-gray-500 text-xs">
             <i class="fas fa-hashtag text-gray-300"></i>
             <span x-text="appointment.id"></span>
         </div>
         <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"  :class="badgeClass(appointment.status)">
             <i :class="fas" :class="statusIcon(appointment.status)"></i>
              <span x-text="appointment.status"></span>
         </span>
     </div>
