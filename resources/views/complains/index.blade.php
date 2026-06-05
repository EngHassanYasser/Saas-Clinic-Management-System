@extends('layouts-main.dashboard')

@section('title', 'الشكاوى')

@section('content')

    @php
        $isClinic = auth()->user()->type === 'clinic';

        $complaints = [
            [
                'patient' => 'أحمد محمد',
                'initials' => 'أم',
                'color' => 'bg-blue-100 text-blue-600',
                'subject' => 'تأخر موعد أكثر من ساعة',
                'against' => 'د. سارة أحمد',
                'priority' => 'urgent',
                'status' => 'reviewing',
                'date' => '2026-05-28',
            ],
            [
                'patient' => 'سلمى إبراهيم',
                'initials' => 'سإ',
                'color' => 'bg-purple-100 text-purple-600',
                'subject' => 'صعوبة في حجز موعد',
                'against' => 'الاستقبال',
                'priority' => 'normal',
                'status' => 'pending',
                'date' => '2026-05-27',
            ],
            [
                'patient' => 'محمود طارق',
                'initials' => 'مط',
                'color' => 'bg-teal-100 text-teal-600',
                'subject' => 'سوء التعامل من الاستقبال',
                'against' => 'الاستقبال',
                'priority' => 'urgent',
                'status' => 'reviewing',
                'date' => '2026-05-29',
            ],
            [
                'patient' => 'هدى سالم',
                'initials' => 'هس',
                'color' => 'bg-amber-100 text-amber-600',
                'subject' => 'عدم وضوح التعليمات الطبية',
                'against' => 'د. خالد منصور',
                'priority' => 'normal',
                'status' => 'pending',
                'date' => '2026-05-26',
            ],
            [
                'patient' => 'كريم عادل',
                'initials' => 'كع',
                'color' => 'bg-rose-100 text-rose-600',
                'subject' => 'خطأ في وصف الدواء',
                'against' => 'د. ريم عبدالله',
                'priority' => 'urgent',
                'status' => 'reviewing',
                'date' => '2026-05-25',
            ],
            [
                'patient' => 'نور الدين',
                'initials' => 'نل',
                'color' => 'bg-green-100 text-green-600',
                'subject' => 'تأخر في نتائج التحاليل',
                'against' => 'المعمل',
                'priority' => 'normal',
                'status' => 'resolved',
                'date' => '2026-05-20',
            ],
            [
                'patient' => 'فاطمة علي',
                'initials' => 'فع',
                'color' => 'bg-pink-100 text-pink-600',
                'subject' => 'إلغاء الموعد بدون إشعار',
                'against' => 'د. سارة أحمد',
                'priority' => 'normal',
                'status' => 'resolved',
                'date' => '2026-05-18',
            ],
            [
                'patient' => 'يوسف حسن',
                'initials' => 'يح',
                'color' => 'bg-indigo-100 text-indigo-600',
                'subject' => 'ارتفاع سعر الكشف بدون إشعار',
                'against' => 'الإدارة',
                'priority' => 'normal',
                'status' => 'resolved',
                'date' => '2026-05-15',
            ],
        ];

        $statusMap = [
            'pending' => ['label' => 'في الانتظار', 'class' => 'bg-blue-100 text-blue-700'],
            'reviewing' => ['label' => 'قيد المراجعة', 'class' => 'bg-amber-100 text-amber-700'],
            'resolved' => ['label' => 'تم الحل', 'class' => 'bg-emerald-100 text-emerald-700'],
        ];

        $priorityMap = [
            'urgent' => ['label' => 'عاجل', 'class' => 'bg-red-100 text-red-600'],
            'normal' => ['label' => 'عادي', 'class' => 'bg-gray-100 text-gray-500'],
        ];
    @endphp

    <div class="p-6 min-h-screen bg-gray-50" dir="rtl">

        <x-complains.add-button />

        {{-- ===================== STATS (clinic only) ===================== --}}

        @if ($isClinic)
            <x-complains.status />
        @endif
          <x-complains.filters />
        <x-complains.table :$complaints :$statusMap :$priorityMap :$isClinic />
    </div>


    <x-complains.details-model :$isClinic />
    <x-complains.delete-model />

    <script>
        const IS_CLINIC = {{ $isClinic ? 'true' : 'false' }}
        const statusMap = {
            pending: {
                label: 'في الانتظار',
                cls: 'bg-blue-100 text-blue-700'
            },
            reviewing: {
                label: 'قيد المراجعة',
                cls: 'bg-amber-100 text-amber-700'
            },
            resolved: {
                label: 'تم الحل',
                cls: 'bg-emerald-100 text-emerald-700'
            },
        };
        const priorityMap = {
            urgent: {
                label: 'عاجل',
                cls: 'bg-red-100 text-red-600'
            },
            normal: {
                label: 'عادي',
                cls: 'bg-gray-100 text-gray-500'
            },
        };

        // ===== Filters (clinic only) =====
        if (IS_CLINIC) {
            document.getElementById('searchInput').addEventListener('input', filterComplaints);
            document.getElementById('statusFilter').addEventListener('change', filterComplaints);
            document.getElementById('priorityFilter').addEventListener('change', filterComplaints);
        }

        function filterComplaints() {
            const search = document.getElementById('searchInput').value.trim().toLowerCase();
            const status = document.getElementById('statusFilter').value;
            const priority = document.getElementById('priorityFilter').value;
            const rows = document.querySelectorAll('.complaint-row');
            let visible = 0;

            rows.forEach(row => {
                const show =
                    (!search || row.dataset.patient.toLowerCase().includes(search) || row.dataset.subject
                        .toLowerCase().includes(search)) &&
                    (!status || row.dataset.status === status) &&
                    (!priority || row.dataset.priority === priority);
                row.classList.toggle('hidden', !show);
                if (show) visible++;
            });

            const empty = document.getElementById('emptyState');
            empty.classList.toggle('hidden', visible > 0);
            empty.classList.toggle('flex', visible === 0);
        }

        // ===== Details Modal =====
        let currentRow = null;

        function openDetails(row) {
            currentRow = row;

            const {
                patient,
                subject,
                against,
                date,
                status,
                priority,
                initials,
                color
            } = row.dataset;
            const st = statusMap[status];
            const pr = priorityMap[priority];

            const avatar = document.getElementById('detailsAvatar');
            avatar.className =
                `w-10 h-10 rounded-xl flex items-center justify-center text-sm font-medium flex-shrink-0 ${color}`;
            avatar.textContent = initials;

            document.getElementById('detailsPatient').textContent = patient;
            document.getElementById('detailsDate').textContent = date;
            document.getElementById('detailsSubject').textContent = subject;
            document.getElementById('detailsAgainst').textContent = against;
            document.getElementById('replyText').value = '';

            const stEl = document.getElementById('detailsStatus');
            stEl.textContent = st.label;
            stEl.className = `text-xs px-2.5 py-1 rounded-full ${st.cls}`;

            const prEl = document.getElementById('detailsPriority');
            prEl.textContent = pr.label;
            prEl.className = `text-xs px-2.5 py-1 rounded-full ${pr.cls}`;

            // Readonly status text for non-clinic
            const roStatus = document.getElementById('readonlyStatus');
            if (roStatus) roStatus.textContent = st.label;

            const modal = document.getElementById('detailsModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDetails() {
            const modal = document.getElementById('detailsModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function changeStatus(newStatus) {
            if (!currentRow) return;
            currentRow.dataset.status = newStatus;
            const st = statusMap[newStatus];
            const badge = currentRow.querySelectorAll('td')[4].querySelector('span');
            badge.textContent = st.label;
            badge.className = `text-xs px-2.5 py-1 rounded-full ${st.cls}`;
            const stEl = document.getElementById('detailsStatus');
            stEl.textContent = st.label;
            stEl.className = `text-xs px-2.5 py-1 rounded-full ${st.cls}`;
        }

        function sendReply() {
            const reply = document.getElementById('replyText').value.trim();
            if (!reply) return;
            changeStatus('resolved');
            closeDetails();
        }

        // ===== Delete (clinic only) =====
        let targetRow = null;

        function confirmDelete(btn) {
            targetRow = btn.closest('tr');
            // إذا كان المودال التفاصيل مفتوحًا، أغلقه أولاً
            closeDetails();
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            targetRow = null;
        }

        function doDelete() {
            if (!targetRow) return;

            const status = targetRow.dataset.status;

            // حذف الصف من الجدول
            targetRow.remove();
            targetRow = null;

            // تحديث الإحصائيات
            updateStats(status);

            closeDeleteModal();
        }

        function updateStats(deletedStatus) {
            // إجمالي الشكاوى
            const totalEl = document.querySelector('[data-stat="total"]');
            if (totalEl) totalEl.textContent = Math.max(0, parseInt(totalEl.textContent) - 1);

            // الحالة المقابلة
            const statEl = document.querySelector(`[data-stat="${deletedStatus}"]`);
            if (statEl) statEl.textContent = Math.max(0, parseInt(statEl.textContent) - 1);
        }

        // ===== Close on backdrop click =====
        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) closeDetails();
        });

        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('click', function(e) {
                if (e.target === this) closeDeleteModal();
            });
        }
    </script>
@endsection
