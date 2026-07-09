  <!-- ACTIONS -->
  <div class="flex flex-wrap gap-2 lg:flex-shrink-0">
      <a href="#"
          class="px-3 py-2 rounded-xl bg-teal-50 border border-teal-100 text-teal-700 text-xs font-semibold hover:bg-teal-100 transition whitespace-nowrap">
          <i class="fas fa-calendar-alt"></i> إعادة جدولة
      </a>
      <button onclick="confirmCancel({{ $appt['id'] }})"
          class="px-3 py-2 rounded-xl bg-red-50 border border-red-100 text-red-600 text-xs font-semibold hover:bg-red-100 transition whitespace-nowrap">
          <i class="fas fa-times"></i> إلغاء
      </button>
  </div>
