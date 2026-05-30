  <!-- ACTIONS -->
  <div class="flex flex-wrap gap-2 justify-end">

      @if ($appt['status'] === 'confirmed' || $appt['status'] === 'pending')

          <a href="#"
              class="px-3 py-2 rounded-xl bg-teal-50 border border-teal-100 text-teal-700 text-xs font-semibold hover:bg-teal-100 transition">
              إعادة جدولة
          </a>

          @if ($appt['can_cancel'])
              <button onclick="confirmCancel({{ $appt['id'] }})"
                  class="px-3 py-2 rounded-xl bg-red-50 border border-red-100 text-red-600 text-xs font-semibold hover:bg-red-100 transition">
                  إلغاء
              </button>
          @endif
      @elseif($appt['status'] === 'completed')
          <a href="#"
              class="px-3 py-2 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 text-xs font-semibold hover:bg-blue-100 transition">
              حجز مرة أخرى
          </a>
      @elseif($appt['status'] === 'cancelled')
          <a href="#"
              class="px-3 py-2 rounded-xl bg-gray-50 border border-gray-100 text-gray-500 text-xs font-semibold hover:bg-gray-100 transition">
              إعادة الحجز
          </a>

      @endif

  </div>
