@extends('layouts-main.dashboard')

@section('title', 'مواعيد الأطباء')

@section('content')

    <div class="p-6 min-h-screen bg-gray-50" dir="rtl">
        @php
            $doctors = [
                (object) [
                    'id' => 1,
                    'name' => 'د. أحمد سامي',
                    'specialty' => 'باطنة',
                    'schedules' => collect([
                        (object) [
                            'id' => 101,
                            'days' => ['saturday', 'monday', 'wednesday'],
                            'work_start' => '09:00',
                            'work_end' => '15:00',
                            'break_start' => '12:00',
                            'break_end' => '12:30',
                            'session_duration' => 30,
                        ],
                        (object) [
                            'id' => 102,
                            'days' => ['tuesday', 'thursday'],
                            'work_start' => '10:00',
                            'work_end' => '16:00',
                            'break_start' => null,
                            'break_end' => null,
                            'session_duration' => 20,
                        ],
                    ]),
                ],

                (object) [
                    'id' => 2,
                    'name' => 'د. منى عبد الرحمن',
                    'specialty' => 'أطفال',
                    'schedules' => collect([
                        (object) [
                            'id' => 201,
                            'days' => ['sunday', 'tuesday', 'thursday'],
                            'work_start' => '11:00',
                            'work_end' => '17:00',
                            'break_start' => '14:00',
                            'break_end' => '14:30',
                            'session_duration' => 15,
                        ],
                    ]),
                ],

                (object) [
                    'id' => 3,
                    'name' => 'د. كريم عبد الله',
                    'specialty' => 'عظام',
                    'schedules' => collect([
                        (object) [
                            'id' => 301,
                            'days' => ['monday', 'wednesday', 'friday'],
                            'work_start' => '09:30',
                            'work_end' => '15:30',
                            'break_start' => '13:00',
                            'break_end' => '13:45',
                            'session_duration' => 45,
                        ],
                    ]),
                ],

                (object) [
                    'id' => 4,
                    'name' => 'د. سارة محمود',
                    'specialty' => 'جلدية',
                    'schedules' => collect([]),
                ],
            ];
        @endphp
        {{-- DOCTORS LIST --}}
        <div class="flex flex-col gap-4">

            @forelse ($doctors as $doctor)

                {{-- DOCTOR CARD --}}
                <div class="bg-white rounded-xl border border-gray-100" x-data="{
                    open: false,
                    showAddModal: false,
                    showEditModal: false,
                    editSchedule: null,
                
                    days: {
                        saturday: 'السبت',
                        sunday: 'الأحد',
                        monday: 'الاثنين',
                        tuesday: 'الثلاثاء',
                        wednesday: 'الأربعاء',
                        thursday: 'الخميس',
                        friday: 'الجمعة',
                    },
                
                    openEdit(schedule) {
                        this.editSchedule = { ...schedule };
                        this.showEditModal = true;
                    }
                }">

                    {{-- DOCTOR ROW --}}
                    <div class="flex items-center justify-between p-4 cursor-pointer select-none" @click="open = !open">

                        <div class="flex items-center gap-3">
                            {{-- Avatar --}}
                            <div
                                class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-sm flex-shrink-0">
                                {{ mb_substr($doctor->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $doctor->name }}</p>
                                <p class="text-xs text-gray-400">{{ $doctor->specialty }} —
                                    {{ $doctor->schedules->count() }} جدول</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" @click.stop="showAddModal = true"
                                class="text-xs bg-teal-50 hover:bg-teal-100 text-teal-700 px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                                <i class="fa fa-plus text-[10px]"></i> إضافة موعد
                            </button>
                            <i class="fa fa-chevron-down text-gray-400 text-xs transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''"></i>
                        </div>
                    </div>

                    {{-- SCHEDULES TABLE --}}
                    <div x-show="open" x-transition x-cloak class="border-t border-gray-100">

                        @if ($doctor->schedules->isEmpty())
                            <div class="py-8 text-center text-sm text-gray-400">
                                <i class="fa fa-calendar-xmark text-2xl mb-2 block text-gray-200"></i>
                                لا توجد مواعيد مضافة بعد
                            </div>
                        @else
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 text-xs text-gray-500">
                                        <th class="text-right px-4 py-2.5 font-medium">الأيام</th>
                                        <th class="text-right px-4 py-2.5 font-medium">من</th>
                                        <th class="text-right px-4 py-2.5 font-medium">إلى</th>
                                        <th class="text-right px-4 py-2.5 font-medium">بريك</th>
                                        <th class="text-right px-4 py-2.5 font-medium">مدة الكشف</th>
                                        <th class="px-4 py-2.5"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach ($doctor->schedules as $schedule)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($schedule->days as $day)
                                                        <span
                                                            class="text-xs bg-teal-50 text-teal-700 border border-teal-100 px-2 py-0.5 rounded-md">
                                                            {{ ['saturday' => 'السبت', 'sunday' => 'الأحد', 'monday' => 'الاثنين', 'tuesday' => 'الثلاثاء', 'wednesday' => 'الأربعاء', 'thursday' => 'الخميس', 'friday' => 'الجمعة'][$day] ?? $day }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">{{ $schedule->work_start }}</td>
                                            <td class="px-4 py-3 text-gray-700">{{ $schedule->work_end }}</td>
                                            <td class="px-4 py-3 text-gray-500 text-xs">
                                                @if ($schedule->break_start && $schedule->break_end)
                                                    {{ $schedule->break_start }} — {{ $schedule->break_end }}
                                                @else
                                                    <span class="text-gray-300">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">{{ $schedule->session_duration }} د</td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2 justify-end">
                                                    <button type="button"
                                                        @click.stop="openEdit(@js($schedule))"
                                                        class="text-xs text-blue-500 hover:text-blue-700 transition">
                                                        <i class="fa fa-pen"></i>
                                                    </button>
                                                    <form action="" method="POST"
                                                        onsubmit="return confirm('تأكيد الحذف؟')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="text-xs text-red-400 hover:text-red-600 transition">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>

                    {{-- ===== ADD MODAL ===== --}}
                    <div x-show="showAddModal" x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
                        @keydown.escape.window="showAddModal = false">

                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg" @click.outside="showAddModal = false">

                            <div class="flex items-center justify-between p-5 border-b">
                                <h2 class="font-semibold text-gray-800">إضافة موعد — {{ $doctor->name }}</h2>
                                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600">
                                    <i class="fa fa-xmark"></i>
                                </button>
                            </div>

                            <form action="" method="POST" x-data="{
                                selectedDays: [],
                                toggleDay(day) {
                                    if (this.selectedDays.includes(day))
                                        this.selectedDays = this.selectedDays.filter(d => d !== day);
                                    else
                                        this.selectedDays.push(day);
                                }
                            }">
                                @csrf
                                <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                                <div class="p-5 flex flex-col gap-4">

                                    {{-- الأيام --}}
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-2">الأيام <span
                                                class="text-red-400">*</span></label>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach (['saturday' => 'السبت', 'sunday' => 'الأحد', 'monday' => 'الاثنين', 'tuesday' => 'الثلاثاء', 'wednesday' => 'الأربعاء', 'thursday' => 'الخميس', 'friday' => 'الجمعة'] as $val => $label)
                                                <button type="button" @click="toggleDay('{{ $val }}')"
                                                    :class="selectedDays.includes('{{ $val }}') ?
                                                        'bg-teal-500 text-white border-teal-500' :
                                                        'text-gray-600 border-gray-200'"
                                                    class="px-3 py-1.5 rounded-lg text-xs border transition">
                                                    {{ $label }}
                                                </button>
                                            @endforeach
                                        </div>
                                        {{-- hidden inputs للأيام --}}
                                        <template x-for="day in selectedDays" :key="day">
                                            <input type="hidden" name="days[]" :value="day">
                                        </template>
                                    </div>

                                    {{-- أوقات العمل --}}
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1.5">بداية الدوام <span
                                                    class="text-red-400">*</span></label>
                                            <input type="time" name="work_start"
                                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1.5">نهاية الدوام <span
                                                    class="text-red-400">*</span></label>
                                            <input type="time" name="work_end"
                                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                                        </div>
                                    </div>

                                    {{-- البريك --}}
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1.5">بداية البريك</label>
                                            <input type="time" name="break_start"
                                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1.5">نهاية البريك</label>
                                            <input type="time" name="break_end"
                                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                                        </div>
                                    </div>

                                    {{-- مدة الكشف --}}
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1.5">مدة الكشف <span
                                                class="text-red-400">*</span></label>
                                        <select name="session_duration"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">
                                            <option value="15">15 دقيقة</option>
                                            <option value="20">20 دقيقة</option>
                                            <option value="30" selected>30 دقيقة</option>
                                            <option value="45">45 دقيقة</option>
                                            <option value="60">60 دقيقة</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="flex gap-2 px-5 pb-5">
                                    <button type="submit"
                                        class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 rounded-lg transition">
                                        حفظ
                                    </button>
                                    <button type="button" @click="showAddModal = false"
                                        class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">
                                        إلغاء
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ===== EDIT MODAL ===== --}}
                    <div x-show="showEditModal" x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
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
                                                <input type="time" name="break_start"
                                                    :value="editSchedule.break_start"
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

                </div>
                {{-- END DOCTOR CARD --}}

            @empty
                <div class="bg-white rounded-xl border border-gray-100 py-16 text-center">
                    <i class="fa fa-user-doctor text-4xl text-gray-200 mb-3 block"></i>
                    <p class="text-gray-400 text-sm">لا يوجد أطباء مضافون بعد</p>
                </div>
            @endforelse

        </div>
    </div>

@endsection
