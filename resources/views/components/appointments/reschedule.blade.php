<div x-show="showRescheduleModal" x-cloak x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div @click.outside="showRescheduleModal = false" class="w-full max-w-3xl rounded-2xl bg-white shadow-xl">

        <div class="flex items-center justify-between border-b px-6 py-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800">
                    إعادة جدولة الموعد
                </h2>
                <p class="text-sm text-gray-500">
                    اختر موعدًا جديدًا للمريض
                </p>
            </div>

            <button @click="showRescheduleModal = false" class="text-gray-400 hover:text-red-500">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-5">

            <!-- التاريخ -->
            <div>
                <label class="text-xs text-gray-500">التاريخ</label>

                <input type="date" x-model="currentAppointment.visit_date"
                    @change="handelAvailbleSlots(currentAppointment.clinic.id,currentAppointment.doctor.id,currentAppointment.visiteDate)"
                    class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-100 outline-none">
            </div>

            <x-appointments.reschedule.available_slots />

        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 border-t px-6 py-4">

            <button @click="showRescheduleModal = false;availableSlots = []";
                class="rounded-xl border border-gray-200 px-5 py-2 text-sm hover:bg-gray-100">
                إلغاء
            </button>
            <form
                :action="'{{ url('appointments') }}/' +
                currentAppointment.id + '/' +
                    currentAppointment.visiteDate + '/' +
                    encodeURIComponent(selectedSlot) +
                    '/availableSlots'"
                method="POST"> @method('PATCH')
                @csrf
                <button class="rounded-xl bg-teal-600 px-5 py-2 text-sm text-white hover:bg-teal-700">
                    حفظ الموعد الجديد
                </button>
            </form>

        </div>

    </div>
</div>
