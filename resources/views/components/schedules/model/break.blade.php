<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="block text-xs text-gray-500 mb-1.5">بداية البريك</label>
        <input type="time" name="startBreak" :value="editSchedule.startBreak.substring(0, 5)"
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1.5">نهاية البريك</label>
        <input type="time" name="endBreak" :value="editSchedule.endBreak.substring(0, 5)"
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
    </div>
</div>
