<div>
    <label class="text-xs text-gray-500 block mb-3">
        المواعيد المتاحة
    </label>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <template x-for="slot in availableSlots" :key="slot">
            <button type="button" @click="selectedSlot = slot" class="rounded-xl border px-4 py-3 text-sm transition"
                :class="selectedSlot === slot ?
                    'bg-teal-500 text-white border-teal-500' :
                    'border-gray-200 hover:border-teal-500 hover:bg-teal-50'">
                <div class="font-semibold" x-text="convertUtcToLocalTime(currentAppointment.visit_date, slot)">
                </div>
                <div class="text-xs opacity-80 mt-1">
                    متاح
                </div>
            </button>
        </template>
    </div>
</div>
