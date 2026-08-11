<div x-show="form.startDate && form.startDate"
    class="bg-teal-50 rounded-lg px-4 py-2.5 text-sm text-teal-700 flex items-center gap-2">
    <i class="fa fa-calendar-days"></i>
    <span x-text="'مدة الإجازة: ' + daysBetween(selectedVacation.startDate, selectedVacation.endDate) + ' يوم'">
    </span>
</div>
