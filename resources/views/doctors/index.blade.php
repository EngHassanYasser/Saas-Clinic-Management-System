@extends('layouts-main.dashboard')

@section('title', 'الأطباء')

@section('content')
    @php
        $doctors = [
            [
                'name' => 'د. سارة أحمد',
                'specialty' => 'قلب وأوعية دموية',
                'price' => 300,
                'appointments' => 92,
                'active' => true,
                'phone' => '1234',
                'email' => 'example@gmail.com',
                'initials' => 'س',
                'color' => 'bg-pink-100 text-pink-600',
            ],
            [
                'name' => 'د. خالد منصور',
                'specialty' => 'عظام',
                'price' => 250,
                'appointments' => 78,
                'phone' => '1234',
                'email' => 'example@gmail.com',
                'active' => true,
                'initials' => 'خ',
                'color' => 'bg-blue-100 text-blue-600',
            ],
            [
                'name' => 'د. ريم عبدالله',
                'specialty' => 'جلدية',
                'price' => 200,
                'appointments' => 65,
                'phone' => '1234',
                'email' => 'example@gmail.com',
                'active' => true,
                'initials' => 'ر',
                'color' => 'bg-purple-100 text-purple-600',
            ],
            [
                'name' => 'د. محمد السيد',
                'specialty' => 'طب عام',
                'price' => 150,
                'appointments' => 48,
                'phone' => '1234',
                'email' => 'example@gmail.com',
                'active' => false,
                'initials' => 'م',
                'color' => 'bg-teal-100 text-teal-600',
            ],
            [
                'name' => 'د. هدى كمال',
                'specialty' => 'أطفال',
                'price' => 220,
                'appointments' => 55,
                'phone' => '1234',
                'email' => 'example@gmail.com',
                'active' => true,
                'initials' => 'ه',
                'color' => 'bg-amber-100 text-amber-600',
            ],
            [
                'name' => 'د. أحمد فاروق',
                'specialty' => 'مخ وأعصاب',
                'price' => 350,
                'appointments' => 30,
                'phone' => '1234',
                'email' => 'example@gmail.com',
                'active' => true,
                'initials' => 'أ',
                'color' => 'bg-green-100 text-green-600',
            ],
            [
                'name' => 'د. منى إبراهيم',
                'specialty' => 'عيون',
                'price' => 180,
                'appointments' => 41,
                'phone' => '1234',
                'email' => 'example@gmail.com',
                'active' => false,
                'initials' => 'م',
                'color' => 'bg-indigo-100 text-indigo-600',
            ],
            [
                'name' => 'د. عمر حسين',
                'specialty' => 'طب عام',
                'price' => 120,
                'appointments' => 60,
                'phone' => '1234',
                'email' => 'example@gmail.com',
                'active' => true,
                'initials' => 'ع',
                'color' => 'bg-rose-100 text-rose-600',
            ],
        ];
    @endphp
    <div x-data="{
        search: '',
        specialty: '',
        status: '',
        showEditModal: false,
        editDoctor: null,
        imagePreview: null,
        doctors: @js($doctors),
    
        get filteredDoctors() {
            const q = this.search.toLowerCase();
    
            return this.doctors.filter(d => {
                const matchSearch =
                    d.name.toLowerCase().includes(q) ||
                    d.specialty.toLowerCase().includes(q);
    
                const matchSpecialty =
                    this.specialty === '' ||
                    d.specialty === this.specialty;
    
                const matchStatus =
                    this.status === '' ||
                    (this.status === 'active' && d.active) ||
                    (this.status === 'inactive' && !d.active);
    
                return matchSearch && matchSpecialty && matchStatus;
            });
        }
    }">
        <x-doctors.stats />

        <div class="flex justify-between ">

            <x-doctors.filters />
            <x-doctors.add-doctor-button />

        </div>
        <x-doctors.grid-view />
        <x-doctors.empty-state />
    </div>
    </div>
@endsection
