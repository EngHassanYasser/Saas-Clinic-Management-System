      <!-- Date -->
      <div class="flex items-start gap-3">

          <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center border border-blue-100">
              <i class="fas fa-calendar-day text-blue-500"></i>
          </div>

          <div class="text-left sm:text-right">
              <p class="text-xs text-gray-400">الموعد</p>
              <p class="font-bold text-gray-900 text-sm">
                  {{ $appt['date'] }}
              </p>

              <span
                  class="inline-flex items-center gap-1 mt-1 text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full border border-blue-100">
                  <i class="fas fa-clock"></i>
                  {{ $appt['time'] }}
              </span>
          </div>

      </div>
