@extends('layouts-main.dashboard')

@section('title', 'الإجازات')

@section('content')

    <div class="p-6 min-h-screen bg-gray-50" dir="rtl">

        {{-- ===================== HEADER ===================== --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-400 mb-1">
                    <a href="" class="hover:text-teal-600">الرئيسية</a>
                    <i class="fa fa-chevron-left text-xs"></i>
                    <a href="" class="hover:text-teal-600">الأطباء</a>
                    <i class="fa fa-chevron-left text-xs"></i>
                    <span class="text-gray-600">الإجازات</span>
                </div>
                <h1 class="text-xl font-medium text-gray-800">إجازات الأطباء</h1>
            </div>
            <button onclick="openModal()"
                class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
                <i class="fa fa-umbrella-beach"></i> إضافة إجازة
            </button>
        </div>

        {{-- ===================== STATS ===================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-teal-50 flex items-center justify-center flex-shrink-0">
                    <i class="fa fa-umbrella-beach text-teal-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">إجمالي الإجازات</p>
                    <p class="text-xl font-medium text-gray-800">12</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                    <i class="fa fa-clock text-amber-500"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">جارية الآن</p>
                    <p class="text-xl font-medium text-gray-800">2</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i class="fa fa-calendar-days text-blue-500"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">قادمة</p>
                    <p class="text-xl font-medium text-gray-800">5</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                    <i class="fa fa-circle-check text-gray-400"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">منتهية</p>
                    <p class="text-xl font-medium text-gray-800">5</p>
                </div>
            </div>
        </div>

        {{-- ===================== FILTERS ===================== --}}
        <div class="bg-white rounded-xl border border-gray-100 p-4 mb-6 flex flex-wrap items-center gap-3">

            <div class="relative flex-1 min-w-48">
                <i class="fa fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input type="text" id="searchInput" placeholder="ابحث باسم الطبيب..."
                    class="w-full border border-gray-200 rounded-lg pr-9 pl-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
            </div>

            <select id="statusFilter"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:border-teal-400 bg-white min-w-36">
                <option value="">كل الحالات</option>
                <option value="active">جارية</option>
                <option value="upcoming">قادمة</option>
                <option value="ended">منتهية</option>
            </select>

            <input type="month" id="monthFilter"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:border-teal-400 bg-white">

        </div>

        {{-- ===================== TABLE ===================== --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-xs text-gray-400 bg-gray-50">
                        <th class="text-right px-4 py-3 font-medium">الطبيب</th>
                        <th class="text-right px-4 py-3 font-medium">السبب</th>
                        <th class="text-right px-4 py-3 font-medium">من</th>
                        <th class="text-right px-4 py-3 font-medium">إلى</th>
                        <th class="text-right px-4 py-3 font-medium">المدة</th>
                        <th class="text-right px-4 py-3 font-medium">الحالة</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="tableBody">

                    @php
                        $vacations = [
                            [
                                'doctor' => 'د. سارة أحمد',
                                'initials' => 'سأ',
                                'color' => 'bg-pink-100 text-pink-600',
                                'reason' => 'إجازة سنوية',
                                'from' => '2026-05-20',
                                'to' => '2026-06-05',
                                'status' => 'active',
                            ],
                            [
                                'doctor' => 'د. خالد منصور',
                                'initials' => 'خم',
                                'color' => 'bg-blue-100 text-blue-600',
                                'reason' => 'ظروف شخصية',
                                'from' => '2026-05-28',
                                'to' => '2026-05-30',
                                'status' => 'active',
                            ],
                            [
                                'doctor' => 'د. ريم عبدالله',
                                'initials' => 'رع',
                                'color' => 'bg-purple-100 text-purple-600',
                                'reason' => 'إجازة سنوية',
                                'from' => '2026-06-10',
                                'to' => '2026-06-20',
                                'status' => 'upcoming',
                            ],
                            [
                                'doctor' => 'د. محمد السيد',
                                'initials' => 'مس',
                                'color' => 'bg-teal-100 text-teal-600',
                                'reason' => 'مؤتمر طبي',
                                'from' => '2026-06-15',
                                'to' => '2026-06-17',
                                'status' => 'upcoming',
                            ],
                            [
                                'doctor' => 'د. هدى كمال',
                                'initials' => 'هك',
                                'color' => 'bg-amber-100 text-amber-600',
                                'reason' => 'إجازة أمومة',
                                'from' => '2026-07-01',
                                'to' => '2026-09-01',
                                'status' => 'upcoming',
                            ],
                            [
                                'doctor' => 'د. أحمد فاروق',
                                'initials' => 'أف',
                                'color' => 'bg-green-100 text-green-600',
                                'reason' => 'إجازة سنوية',
                                'from' => '2026-04-01',
                                'to' => '2026-04-10',
                                'status' => 'ended',
                            ],
                            [
                                'doctor' => 'د. منى إبراهيم',
                                'initials' => 'مإ',
                                'color' => 'bg-indigo-100 text-indigo-600',
                                'reason' => 'ظروف عائلية',
                                'from' => '2026-03-15',
                                'to' => '2026-03-20',
                                'status' => 'ended',
                            ],
                            [
                                'doctor' => 'د. عمر حسين',
                                'initials' => 'عح',
                                'color' => 'bg-rose-100 text-rose-600',
                                'reason' => 'تدريب خارجي',
                                'from' => '2026-02-10',
                                'to' => '2026-02-25',
                                'status' => 'ended',
                            ],
                        ];

                        $statusMap = [
                            'active' => ['label' => 'جارية', 'class' => 'bg-amber-100 text-amber-700'],
                            'upcoming' => ['label' => 'قادمة', 'class' => 'bg-blue-100 text-blue-700'],
                            'ended' => ['label' => 'منتهية', 'class' => 'bg-gray-100 text-gray-500'],
                        ];
                    @endphp

                    @foreach ($vacations as $v)
                        @php
                            $from = \Carbon\Carbon::parse($v['from']);
                            $to = \Carbon\Carbon::parse($v['to']);
                            $days = $from->diffInDays($to) + 1;
                            $st = $statusMap[$v['status']];
                        @endphp
                        <tr class="vacation-row hover:bg-gray-50 transition" data-doctor="{{ $v['doctor'] }}"
                            data-status="{{ $v['status'] }}" data-month="{{ $from->format('Y-m') }}">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-8 h-8 rounded-lg {{ $v['color'] }} flex items-center justify-center text-xs font-medium flex-shrink-0">
                                        {{ $v['initials'] }}
                                    </div>
                                    <span class="font-medium text-gray-700">{{ $v['doctor'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $v['reason'] }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $from->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $to->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs text-gray-500">{{ $days }} يوم</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2.5 py-1 rounded-full {{ $st['class'] }}">
                                    {{ $st['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <button onclick="editVacation(this)"
                                        class="text-teal-500 hover:text-teal-700 transition">
                                        <i class="fa fa-pen text-xs"></i>
                                    </button>
                                    <button onclick="confirmDelete(this)"
                                        class="text-red-400 hover:text-red-600 transition">
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            {{-- Empty State --}}
            <div id="emptyState" class="hidden flex-col items-center justify-center py-16 text-gray-300">
                <i class="fa fa-umbrella-beach text-5xl mb-4"></i>
                <p class="text-sm">لا توجد إجازات مطابقة</p>
            </div>

        </div>

    </div>

    {{-- ===================== ADD / EDIT MODAL ===================== --}}
    <div id="vacationModal" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center" dir="rtl">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-xl">

            <div class="flex items-center justify-between mb-5">
                <h3 id="modalTitle" class="text-base font-medium text-gray-800">إضافة إجازة</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fa fa-xmark"></i>
                </button>
            </div>

            <div class="flex flex-col gap-4">

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">الطبيب <span class="text-red-400">*</span></label>
                    <select id="modalDoctor"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">
                        <option value="">اختر الطبيب</option>
                        <option>د. سارة أحمد</option>
                        <option>د. خالد منصور</option>
                        <option>د. ريم عبدالله</option>
                        <option>د. محمد السيد</option>
                        <option>د. هدى كمال</option>
                        <option>د. أحمد فاروق</option>
                        <option>د. منى إبراهيم</option>
                        <option>د. عمر حسين</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">سبب الإجازة <span
                            class="text-red-400">*</span></label>
                    <select id="modalReason"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">
                        <option value="">اختر السبب</option>
                        <option>إجازة سنوية</option>
                        <option>ظروف شخصية</option>
                        <option>ظروف عائلية</option>
                        <option>إجازة أمومة</option>
                        <option>مؤتمر طبي</option>
                        <option>تدريب خارجي</option>
                        <option>مرض</option>
                        <option>أخرى</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">من <span class="text-red-400">*</span></label>
                        <input type="date" id="modalFrom"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">إلى <span class="text-red-400">*</span></label>
                        <input type="date" id="modalTo"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                    </div>
                </div>

                {{-- Duration Preview --}}
                <div id="durationPreview"
                    class="hidden bg-teal-50 rounded-lg px-4 py-2.5 text-sm text-teal-700 flex items-center gap-2">
                    <i class="fa fa-calendar-days"></i>
                    <span id="durationText"></span>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">ملاحظات</label>
                    <textarea id="modalNotes" rows="3"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition resize-none"
                        placeholder="أي ملاحظات إضافية..."></textarea>
                </div>

            </div>

            <div class="flex gap-3 mt-5">
                <button onclick="closeModal()"
                    class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">
                    إلغاء
                </button>
                <button onclick="saveVacation()"
                    class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm py-2.5 rounded-lg transition">
                    حفظ
                </button>
            </div>

        </div>
    </div>

    {{-- ===================== DELETE MODAL ===================== --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center" dir="rtl">
        <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 text-center shadow-xl">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <i class="fa fa-trash text-red-500 text-xl"></i>
            </div>
            <h3 class="text-base font-medium text-gray-800 mb-2">حذف الإجازة</h3>
            <p class="text-sm text-gray-400 mb-6">هل أنت متأكد من حذف هذه الإجازة؟</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">
                    إلغاء
                </button>
                <button onclick="doDelete()"
                    class="flex-1 bg-red-500 hover:bg-red-600 text-white text-sm py-2.5 rounded-lg transition">
                    حذف
                </button>
            </div>
        </div>
    </div>
    <script>
        // ===== Filters =====
        document.getElementById('searchInput').addEventListener('input', filter);
        document.getElementById('statusFilter').addEventListener('change', filter);
        document.getElementById('monthFilter').addEventListener('change', filter);

        function filter() {
            const search = document.getElementById('searchInput').value.trim().toLowerCase();
            const status = document.getElementById('statusFilter').value;
            const month = document.getElementById('monthFilter').value;
            const rows = document.querySelectorAll('.vacation-row');
            let visible = 0;

            rows.forEach(row => {
                const show = (!search || row.dataset.doctor.toLowerCase().includes(search)) &&
                    (!status || row.dataset.status === status) &&
                    (!month || row.dataset.month === month);
                row.classList.toggle('hidden', !show);
                if (show) visible++;
            });

            const empty = document.getElementById('emptyState');
            if (visible === 0) {
                empty.classList.remove('hidden');
                empty.classList.add('flex');
            } else {
                empty.classList.add('hidden');
                empty.classList.remove('flex');
            }
        }

        // ===== Duration Preview =====
        ['modalFrom', 'modalTo'].forEach(id => {
            document.getElementById(id).addEventListener('change', updateDuration);
        });

        function updateDuration() {
            const from = document.getElementById('modalFrom').value;
            const to = document.getElementById('modalTo').value;
            const prev = document.getElementById('durationPreview');
            const text = document.getElementById('durationText');
            if (!from || !to) {
                prev.classList.add('hidden');
                return;
            }
            const diff = Math.round((new Date(to) - new Date(from)) / 86400000) + 1;
            if (diff <= 0) {
                prev.classList.add('hidden');
                return;
            }
            prev.classList.remove('hidden');
            text.textContent = `مدة الإجازة: ${diff} يوم`;
        }

        // ===== Vacation Modal =====
        function openModal() {
            document.getElementById('modalTitle').textContent = 'إضافة إجازة';
            document.getElementById('modalDoctor').value = '';
            document.getElementById('modalReason').value = '';
            document.getElementById('modalFrom').value = '';
            document.getElementById('modalTo').value = '';
            document.getElementById('modalNotes').value = '';
            document.getElementById('durationPreview').classList.add('hidden');
            const modal = document.getElementById('vacationModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('vacationModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function saveVacation() {
            closeModal();
        }

        function editVacation(btn) {
            document.getElementById('modalTitle').textContent = 'تعديل الإجازة';
            const modal = document.getElementById('vacationModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // ===== Delete Modal =====
        let targetRow = null;

        function confirmDelete(btn) {
            targetRow = btn.closest('tr');
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            targetRow = null;
        }

        function doDelete() {
            if (targetRow) targetRow.remove();
            closeDeleteModal();
        }

        document.getElementById('vacationModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });
    </script>

@endsection
