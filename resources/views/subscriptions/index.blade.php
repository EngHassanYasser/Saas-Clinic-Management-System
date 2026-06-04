@extends('layouts-main.dashboard')

@section('title', 'إدارة الاشتراكات')

@section('content')

    <div dir="rtl" x-data="subscriptionsApp" class="p-6 bg-gray-50 min-h-screen">

        <x-subscriptions.header />

        <x-subscriptions.kpi-cards />
        <x-subscriptions.filters />
        <x-subscriptions.table />

        <x-subscriptions.model />

    </div>

    {{-- ===== ALPINE COMPONENT — قبل تحميل Alpine ===== --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('subscriptionsApp', () => ({

                subscriptions: [{
                        id: 1,
                        clinic: 'عيادة النور',
                        plan: 'premium',
                        price: 500,
                        start: '2026-01-01',
                        end: '2027-01-01'
                    },
                    {
                        id: 2,
                        clinic: 'عيادة الشفاء',
                        plan: 'basic',
                        price: 200,
                        start: '2025-01-01',
                        end: '2026-01-01'
                    },
                    {
                        id: 3,
                        clinic: 'عيادة الرحمة',
                        plan: 'enterprise',
                        price: 900,
                        start: '2026-05-01',
                        end: '2027-05-01'
                    },
                    {
                        id: 4,
                        clinic: 'عيادة الحياة',
                        plan: 'premium',
                        price: 650,
                        start: '2025-06-01',
                        end: '2025-12-20'
                    },
                ],

                search: '',
                statusFilter: '',
                planFilter: '',

                showModal: false,
                editId: null,
                formError: '',

                form: {
                    clinic: '',
                    plan: '',
                    price: '',
                    start: '',
                    end: ''
                },

                get filtered() {
                    return this.subscriptions.filter(item => {
                        const matchSearch = !this.search ||
                            item.clinic.toLowerCase().includes(this.search.toLowerCase()) ||
                            item.plan.toLowerCase().includes(this.search.toLowerCase());
                        const matchStatus = !this.statusFilter || this.getStatus(item) ===
                            this.statusFilter;
                        const matchPlan = !this.planFilter || item.plan === this.planFilter;
                        return matchSearch && matchStatus && matchPlan;
                    });
                },

                getStatus(item) {
                    const now = new Date();
                    const end = new Date(item.end);
                    const diff = (end - now) / (1000 * 60 * 60 * 24);
                    if (end < now) return 'expired';
                    if (diff <= 7) return 'expiring';
                    return 'active';
                },

                statusLabel(status) {
                    return {
                        active: 'نشط',
                        expired: 'منتهي',
                        expiring: 'قريب الانتهاء'
                    } [status] ?? '';
                },

                badgeClass(status) {
                    if (status === 'active') return 'text-green-600 bg-green-50';
                    if (status === 'expiring') return 'text-amber-600 bg-amber-50';
                    return 'text-red-600 bg-red-50';
                },

                countByStatus(status) {
                    return this.subscriptions.filter(x => this.getStatus(x) === status).length;
                },

                deleteItem(id) {
                    if (!confirm('هل أنت متأكد من الحذف؟')) return;
                    this.subscriptions = this.subscriptions.filter(x => x.id !== id);
                },

                renew(id) {
                    const item = this.subscriptions.find(x => x.id === id);
                    const newEnd = new Date();
                    newEnd.setMonth(newEnd.getMonth() + 12);
                    item.end = newEnd.toISOString().split('T')[0];
                },

                openAdd() {
                    this.editId = null;
                    this.formError = '';
                    this.form = {
                        clinic: '',
                        plan: '',
                        price: '',
                        start: '',
                        end: ''
                    };
                    this.showModal = true;
                },

                openEdit(item) {
                    this.editId = item.id;
                    this.formError = '';
                    this.form = {
                        ...item
                    };
                    this.showModal = true;
                },

                save() {
                    if (!this.form.clinic.trim()) {
                        this.formError = 'اسم العيادة مطلوب';
                        return;
                    }
                    if (!this.form.plan) {
                        this.formError = 'الخطة مطلوبة';
                        return;
                    }
                    if (!this.form.price) {
                        this.formError = 'السعر مطلوب';
                        return;
                    }
                    if (!this.form.start) {
                        this.formError = 'تاريخ البداية مطلوب';
                        return;
                    }
                    if (!this.form.end) {
                        this.formError = 'تاريخ الانتهاء مطلوب';
                        return;
                    }

                    this.formError = '';

                    if (this.editId) {
                        const idx = this.subscriptions.findIndex(x => x.id === this.editId);
                        this.subscriptions[idx] = {
                            ...this.form,
                            id: this.editId,
                            price: Number(this.form.price)
                        };
                    } else {
                        this.subscriptions.push({
                            ...this.form,
                            id: Date.now(),
                            price: Number(this.form.price)
                        });
                    }

                    this.showModal = false;
                },

            }));
        });
    </script>

@endsection
