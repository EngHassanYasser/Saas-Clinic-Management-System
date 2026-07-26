<div x-show="selected.slot" x-transition class="fade-in bg-teal-50 border border-teal-100 rounded-xl p-4">
<p class="text-xs text-teal-700 font-semibold mb-2">ملخص الموعد</p>
<div class="space-y-1 text-sm text-gray-700">
        <p><span class="text-gray-500">التخصص:</span> <span class="font-medium"
                x-text="selected.specialty?.name"></span></p>
        <p><span class="text-gray-500">العيادة:</span> <span class="font-medium" x-text="selected.clinic?.name"></span>
        </p>
        <p><span class="text-gray-500">الدكتور:</span> <span class="font-medium"
                x-text="selected.doctor?.name"></span></p>
        <p><span class="text-gray-500">اليوم:</span> <span class="font-medium"
                x-text="selected.date?.fullLabel"></span></p>
        <p><span class="text-gray-500">الوقت:</span> <span class="font-medium" x-text="selected.slot?.label"></span>
        </p>
</div>
</div>
