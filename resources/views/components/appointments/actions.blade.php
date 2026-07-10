<!-- ACTIONS -->
<div class="flex flex-wrap gap-2 lg:flex-shrink-0">

    <!-- Reschedule -->
    <a href="#"
        class="px-3 py-2 rounded-xl bg-blue-50 border border-blue-100 text-blue-700 text-xs font-semibold hover:bg-blue-100 transition whitespace-nowrap">
        <i class="fas fa-calendar-alt"></i> إعادة جدولة
    </a>

    <!-- Complete -->
    <a href="#"
        class="px-3 py-2 rounded-xl bg-green-50 border border-green-100 text-green-700 text-xs font-semibold hover:bg-green-100 transition whitespace-nowrap">
        <i class="fas fa-check-circle"></i> اكتملت
    </a>

    <!-- Cancel -->
    <button onclick="confirmCancel({{ $appt['id'] }})"
        class="px-3 py-2 rounded-xl bg-red-50 border border-red-100 text-red-700 text-xs font-semibold hover:bg-red-100 transition whitespace-nowrap">
        <i class="fas fa-times"></i> إلغاء
    </button>
</div>
