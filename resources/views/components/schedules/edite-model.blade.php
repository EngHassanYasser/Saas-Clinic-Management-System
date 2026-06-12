    {{-- ===== EDIT MODAL ===== --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
        @keydown.escape.window="showEditModal = false">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg" @click.outside="showEditModal = false"
            x-data="{
                get selectedDays() { return this.$root.editSchedule?.days ?? []; },
                toggleDay(day) {
                    const days = this.$root.editSchedule.days;
                    if (days.includes(day))
                        this.$root.editSchedule.days = days.filter(d => d !== day);
                    else
                        this.$root.editSchedule.days = [...days, day];
                }
            }">

            <div class="flex items-center justify-between p-5 border-b">
                <h2 class="font-semibold text-gray-800">تعديل الموعد</h2>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fa fa-xmark"></i>
                </button>
            </div>

            <template x-if="editSchedule">
                <form :action="`/schedules/${editSchedule.id}`" method="POST">
                    @csrf @method('PUT')

                    <div class="p-5 flex flex-col gap-4">

                        {{-- الأيام --}}
                        <div>
                            <label class="block text-xs text-gray-500 mb-2">الأيام</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach (['saturday' => 'السبت', 'sunday' => 'الأحد', 'monday' => 'الاثنين', 'tuesday' => 'الثلاثاء', 'wednesday' => 'الأربعاء', 'thursday' => 'الخميس', 'friday' => 'الجمعة'] as $val => $label)
                                    <button type="button" @click="toggleDay('{{ $val }}')"
                                        :class="editSchedule.days.includes('{{ $val }}') ?
                                            'bg-teal-500 text-white border-teal-500' :
                                            'text-gray-600 border-gray-200'"
                                        class="px-3 py-1.5 rounded-lg text-xs border transition">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                            <template x-for="day in editSchedule.days" :key="day">
                                <input type="hidden" name="days[]" :value="day">
                            </template>
                        </div>

                        {{-- أوقات العمل --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1.5">بداية الدوام</label>
                                <input type="time" name="work_start" :value="editSchedule.work_start"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1.5">نهاية الدوام</label>
                                <input type="time" name="work_end" :value="editSchedule.work_end"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                            </div>
                        </div>

                        {{-- البريك --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1.5">بداية البريك</label>
                                <input type="time" name="break_start" :value="editSchedule.break_start"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1.5">نهاية البريك</label>
                                <input type="time" name="break_end" :value="editSchedule.break_end"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                            </div>
                        </div>

                        {{-- مدة الكشف --}}
                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">مدة الكشف</label>
                            <select name="session_duration"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">
                                @foreach ([15, 20, 30, 45, 60] as $d)
                                    <option value="{{ $d }}"
                                        :selected="editSchedule.session_duration == {{ $d }}">
                                        {{ $d }} دقيقة
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- هل الموعد متاح --}}
                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">
                                هل الموعد متاح
                            </label>

                            <select name="is_available"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">

                                <option value="1" :selected="editSchedule.is_available == 1">
                                    نعم
                                </option>

                                <option value="0" :selected="editSchedule.is_available == 0">
                                    لا
                                </option>

                            </select>
                        </div>

                    </div>

                    <div class="flex gap-2 px-5 pb-5">
                        <button type="submit"
                            class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 rounded-lg transition">
                            حفظ التعديلات
                        </button>
                        <button type="button" @click="showEditModal = false"
                            class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">
                            إلغاء
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>
