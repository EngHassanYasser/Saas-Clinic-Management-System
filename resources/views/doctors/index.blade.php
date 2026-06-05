@extends('layouts-main.dashboard')

@section('title', 'الأطباء')

@section('content')

    <x-doctors.stats />
    <div class="flex justify-between ">
        <x-doctors.filters />
        <x-doctors.add-doctor-button />
    </div>

    @php
        $doctors = [
            [
                'name' => 'د. سارة أحمد',
                'specialty' => 'قلب وأوعية دموية',
                'price' => 300,
                'duration' => 30,
                'appointments' => 92,
                'days' => 'السبت - الأربعاء',
                'active' => true,
                'initials' => 'س',
                'color' => 'bg-pink-100 text-pink-600',
            ],
            [
                'name' => 'د. خالد منصور',
                'specialty' => 'عظام',
                'price' => 250,
                'duration' => 20,
                'appointments' => 78,
                'days' => 'الأحد - الخميس',
                'active' => true,
                'initials' => 'خ',
                'color' => 'bg-blue-100 text-blue-600',
            ],
            [
                'name' => 'د. ريم عبدالله',
                'specialty' => 'جلدية',
                'price' => 200,
                'duration' => 15,
                'appointments' => 65,
                'days' => 'السبت - الثلاثاء',
                'active' => true,
                'initials' => 'ر',
                'color' => 'bg-purple-100 text-purple-600',
            ],
            [
                'name' => 'د. محمد السيد',
                'specialty' => 'طب عام',
                'price' => 150,
                'duration' => 15,
                'appointments' => 48,
                'days' => 'الأحد - الخميس',
                'active' => false,
                'initials' => 'م',
                'color' => 'bg-teal-100 text-teal-600',
            ],
            [
                'name' => 'د. هدى كمال',
                'specialty' => 'أطفال',
                'price' => 220,
                'duration' => 20,
                'appointments' => 55,
                'days' => 'السبت - الأربعاء',
                'active' => true,
                'initials' => 'ه',
                'color' => 'bg-amber-100 text-amber-600',
            ],
            [
                'name' => 'د. أحمد فاروق',
                'specialty' => 'مخ وأعصاب',
                'price' => 350,
                'duration' => 45,
                'appointments' => 30,
                'days' => 'الاثنين - الخميس',
                'active' => true,
                'initials' => 'أ',
                'color' => 'bg-green-100 text-green-600',
            ],
            [
                'name' => 'د. منى إبراهيم',
                'specialty' => 'عيون',
                'price' => 180,
                'duration' => 15,
                'appointments' => 41,
                'days' => 'الأحد - الثلاثاء',
                'active' => false,
                'initials' => 'م',
                'color' => 'bg-indigo-100 text-indigo-600',
            ],
            [
                'name' => 'د. عمر حسين',
                'specialty' => 'طب عام',
                'price' => 120,
                'duration' => 15,
                'appointments' => 60,
                'days' => 'السبت - الخميس',
                'active' => true,
                'initials' => 'ع',
                'color' => 'bg-rose-100 text-rose-600',
            ],
        ];
    @endphp


    <x-doctors.grid-view :$doctors />

    <x-doctors.list-view :$doctors />

    <x-doctors.empty-state />

    </div>

    <x-doctors.delete--model />
    <script>
        let currentView = 'grid';

        function setView(view) {
            currentView = view;
            const grid = document.getElementById('gridContainer');
            const list = document.getElementById('listContainer');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');

            if (view === 'grid') {
                grid.classList.remove('hidden');
                list.classList.add('hidden');
                gridBtn.classList.add('bg-teal-500', 'text-white');
                gridBtn.classList.remove('text-gray-400');
                listBtn.classList.remove('bg-teal-500', 'text-white');
                listBtn.classList.add('text-gray-400');
            } else {
                grid.classList.add('hidden');
                list.classList.remove('hidden');
                listBtn.classList.add('bg-teal-500', 'text-white');
                listBtn.classList.remove('text-gray-400');
                gridBtn.classList.remove('bg-teal-500', 'text-white');
                gridBtn.classList.add('text-gray-400');
            }
            filterDoctors();
        }

        document.getElementById('searchInput').addEventListener('input', filterDoctors);
        document.getElementById('specialtyFilter').addEventListener('change', filterDoctors);
        document.getElementById('statusFilter').addEventListener('change', filterDoctors);

        function filterDoctors() {
            const search = document.getElementById('searchInput').value.trim().toLowerCase();
            const specialty = document.getElementById('specialtyFilter').value.toLowerCase();
            const status = document.getElementById('statusFilter').value;

            const cards = document.querySelectorAll('.doctor-card');
            const rows = document.querySelectorAll('.doctor-row');
            let visible = 0;

            const match = el => {
                const name = el.dataset.name.toLowerCase();
                const sp = el.dataset.specialty.toLowerCase();
                const st = el.dataset.status;
                return (!search || name.includes(search)) &&
                    (!specialty || sp.includes(specialty)) &&
                    (!status || st === status);
            };

            cards.forEach(el => {
                const show = match(el);
                el.classList.toggle('hidden', !show);
                if (show) visible++;
            });
            rows.forEach(el => {
                el.classList.toggle('hidden', !match(el));
            });

            const empty = document.getElementById('emptyState');
            if (visible === 0 && currentView === 'grid') {
                empty.classList.remove('hidden');
                empty.classList.add('flex');
            } else {
                empty.classList.add('hidden');
                empty.classList.remove('flex');
            }
        }

        let targetCard = null;

        function confirmDelete(btn) {
            targetCard = btn.closest('.doctor-card') || btn.closest('tr');
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            targetCard = null;
        }

        function deleteDoctor() {
            if (targetCard) targetCard.remove();
            closeModal();
        }

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        let editingCard = null;

        function openEdit(btn) {
            editingCard = btn.closest('.doctor-card') || btn.closest('tr');

            document.getElementById('editDoctorId').value = editingCard.dataset.id;
            document.getElementById('editName').value = editingCard.dataset.name;
            document.getElementById('editSpecialty').value = editingCard.dataset.specialty;
            document.getElementById('editStatus').value = editingCard.dataset.status;

            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            editingCard = null;
        }

        function saveDoctor() {
            if (!editingCard) return;

            const name = document.getElementById('editName').value.trim();
            const specialty = document.getElementById('editSpecialty').value.trim();
            const status = document.getElementById('editStatus').value;

            // update dataset
            editingCard.dataset.name = name;
            editingCard.dataset.specialty = specialty;
            editingCard.dataset.status = status;

            // update UI (grid)
            const nameEl = editingCard.querySelector('.doctor-name');
            const specEl = editingCard.querySelector('.doctor-specialty');

            if (nameEl) nameEl.textContent = name;
            if (specEl) specEl.textContent = specialty;

            // refresh filters
            filterDoctors();

            closeEditModal();
        }

        // close on outside click
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });
    </script>

@endsection
