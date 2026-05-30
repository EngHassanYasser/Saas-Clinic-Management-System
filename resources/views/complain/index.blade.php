@extends('layouts-main.dashboard')

@section('title', 'الشكاوى')

@section('content')

    <div class="p-6 min-h-screen bg-gray-50" dir="rtl">
        {{-- ===================== STATS ===================== --}}
        @if (auth()->user()->type == 'clinic')
            <x-complain.status />
            <x-complain.filters />
        @endif

        <x-complain.table />

    </div>
    @if (auth()->user()->type == 'clinic')
        <x-complain.details-model />
        <x-complain.delete-model />
    @endif

    <script>
        // ===== Filters =====
        document
            .getElementById("searchInput")
            .addEventListener("input", filterComplaints);
        document
            .getElementById("statusFilter")
            .addEventListener("change", filterComplaints);
        document
            .getElementById("priorityFilter")
            .addEventListener("change", filterComplaints);

        function filterComplaints() {
            const search = document
                .getElementById("searchInput")
                .value.trim()
                .toLowerCase();
            const status = document.getElementById("statusFilter").value;
            const priority = document.getElementById("priorityFilter").value;
            const rows = document.querySelectorAll(".complaint-row");
            let visible = 0;

            rows.forEach((row) => {
                const show =
                    (!search ||
                        row.dataset.patient.toLowerCase().includes(search) ||
                        row.dataset.subject.toLowerCase().includes(search)) &&
                    (!status || row.dataset.status === status) &&
                    (!priority || row.dataset.priority === priority);
                row.classList.toggle("hidden", !show);
                if (show) visible++;
            });

            const empty = document.getElementById("emptyState");
            if (visible === 0) {
                empty.classList.remove("hidden");
                empty.classList.add("flex");
            } else {
                empty.classList.add("hidden");
                empty.classList.remove("flex");
            }
        }

        // ===== Details Modal =====
        const statusMap = {
            pending: {
                label: "في الانتظار",
                cls: "bg-blue-100 text-blue-700",
            },
            reviewing: {
                label: "قيد المراجعة",
                cls: "bg-amber-100 text-amber-700",
            },
            resolved: {
                label: "تم الحل",
                cls: "bg-emerald-100 text-emerald-700",
            },
        };
        const priorityMap = {
            urgent: {
                label: "عاجل",
                cls: "bg-red-100 text-red-600",
            },
            normal: {
                label: "عادي",
                cls: "bg-gray-100 text-gray-500",
            },
        };

        let currentRow = null;

        function openDetails(row) {
            currentRow = row;
            const tds = row.querySelectorAll("td");

            document.getElementById("detailsAvatar").className =
                `w-10 h-10 rounded-xl flex items-center justify-center text-sm font-medium flex-shrink-0 ${tds[0].querySelector("div div").className.replace("w-8 h-8 rounded-lg", "").trim()}`;
            document.getElementById("detailsAvatar").textContent = tds[0]
                .querySelector("div div")
                .textContent.trim();
            document.getElementById("detailsPatient").textContent = row.dataset.patient;
            document.getElementById("detailsDate").textContent =
                tds[5].textContent.trim();
            document.getElementById("detailsSubject").textContent = row.dataset.subject;
            document.getElementById("detailsAgainst").textContent =
                tds[2].textContent.trim();

            const st = statusMap[row.dataset.status];
            const pr = priorityMap[row.dataset.priority];
            const stEl = document.getElementById("detailsStatus");
            const prEl = document.getElementById("detailsPriority");
            stEl.textContent = st.label;
            stEl.className = `text-xs px-2.5 py-1 rounded-full ${st.cls}`;
            prEl.textContent = pr.label;
            prEl.className = `text-xs px-2.5 py-1 rounded-full ${pr.cls}`;

            document.getElementById("replyText").value = "";
            const modal = document.getElementById("detailsModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }

        function closeDetails() {
            const modal = document.getElementById("detailsModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }

        function changeStatus(newStatus) {
            if (!currentRow) return;
            currentRow.dataset.status = newStatus;
            const st = statusMap[newStatus];
            const badge = currentRow.querySelectorAll("td")[4].querySelector("span");
            badge.textContent = st.label;
            badge.className = `text-xs px-2.5 py-1 rounded-full ${st.cls}`;
            const detailsSt = document.getElementById("detailsStatus");
            detailsSt.textContent = st.label;
            detailsSt.className = `text-xs px-2.5 py-1 rounded-full ${st.cls}`;
        }

        function sendReply() {
            const reply = document.getElementById("replyText").value.trim();
            if (!reply) return;
            changeStatus("resolved");
            closeDetails();
        }

        // ===== Delete =====
        let targetRow = null;

        function confirmDelete(btn) {
            targetRow = btn.closest("tr");
            const modal = document.getElementById("deleteModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }

        function closeDeleteModal() {
            const modal = document.getElementById("deleteModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
            targetRow = null;
        }

        function doDelete() {
            if (targetRow) targetRow.remove();
            closeDeleteModal();
        }

        document.getElementById("detailsModal").addEventListener("click", function(e) {
            if (e.target === this) closeDetails();
        });
        document.getElementById("deleteModal").addEventListener("click", function(e) {
            if (e.target === this) closeDeleteModal();
        });
    </script>

@endsection
