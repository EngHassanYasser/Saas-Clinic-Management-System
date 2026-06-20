@extends('layouts-main.dashboard')

@section('title', 'خدمات العيادة')

@section('content')

<div
x-data="{
    showModal: false,
    editMode: false,

    filters: {
        doctorId: '',
        search: ''
    },

    form: {
        id: null,
        name: '',
        description: '',
        doctor: '',
        price: 0
    },

    clinicDoctors: [
        { id: 1, name: 'دكتور أحمد' },
        { id: 2, name: 'دكتورة سارة' },
        { id: 3, name: 'دكتور محمد' },
    ],

    clinicServices: [
        { id: 1, name: 'كشف عام', description: 'كشف شامل', doctorId: 1, doctor: 'دكتور أحمد', price: 200 },
        { id: 2, name: 'متابعة', description: 'متابعة حالة', doctorId: 2, doctor: 'دكتورة سارة', price: 150 },
        { id: 3, name: 'أشعة', description: 'أشعة تشخيصية', doctorId: 3, doctor: 'دكتور محمد', price: 300 },
    ],

    get filteredServices() {
        return this.clinicServices.filter(s => {
            let matchDoctor = this.filters.doctorId ? s.doctorId == this.filters.doctorId : true;
            let matchSearch = this.filters.search
                ? s.name.includes(this.filters.search)
                : true;

            return matchDoctor && matchSearch;
        });
    },

    openCreate() {
        this.editMode = false;
        this.form = { id:null, name:'', description:'', doctor:'', price:0 };
        this.showModal = true;
    },

    openEdit(item) {
        this.editMode = true;
        this.form = { ...item };
        this.showModal = true;
    },

    save() {
        if (this.editMode) {
            let i = this.clinicServices.findIndex(s => s.id === this.form.id);
            if (i !== -1) this.clinicServices[i] = { ...this.form };
        } else {
            this.form.id = Date.now();
            this.clinicServices.push({ ...this.form });
        }
        this.showModal = false;
    },

    deleteService(id) {
        this.clinicServices = this.clinicServices.filter(s => s.id !== id);
    }
}"
dir="rtl"
class="p-6 bg-gray-50 min-h-screen"
>

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">خدمات العيادة</h2>
        <p class="text-sm text-gray-500">إدارة الخدمات والأسعار والأطباء</p>
    </div>

    <button
        @click="openCreate()"
        class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition"
    >
        + إضافة خدمة
    </button>
</div>

<!-- Filters -->
<div class="bg-white p-4 rounded-xl shadow-sm mb-6 grid grid-cols-1 md:grid-cols-3 gap-3">

    <input
        x-model="filters.search"
        type="text"
        placeholder="ابحث باسم الخدمة..."
        class="w-full bg-gray-50 border border-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
    />

    <select
        x-model="filters.doctorId"
        class="w-full bg-gray-50 border border-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none"
    >
        <option value="">كل الدكاترة</option>
        <template x-for="doc in clinicDoctors" :key="doc.id">
            <option :value="doc.id" x-text="doc.name"></option>
        </template>
    </select>

    <div class="flex items-center text-sm text-gray-500">
        عدد النتائج:
        <span class="font-semibold text-gray-900 mx-1" x-text="filteredServices.length"></span>
    </div>

</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500">
            <tr>
                <th class="p-4 text-right font-medium">الخدمة</th>
                <th class="p-4 text-right font-medium">الوصف</th>
                <th class="p-4 text-right font-medium">الدكتور</th>
                <th class="p-4 text-right font-medium">السعر</th>
                <th class="p-4 text-center font-medium">الإجراءات</th>
            </tr>
        </thead>

        <tbody>
            <template x-for="item in filteredServices" :key="item.id">
                <tr class="hover:bg-gray-50/60 transition">
                    <td class="p-4 text-gray-800 font-medium" x-text="item.name"></td>

                    <td class="p-4 text-gray-500" x-text="item.description"></td>

                    <td class="p-4 text-gray-600" x-text="item.doctor"></td>

                    <td class="p-4 text-teal-600 font-semibold"
                        x-text="item.price + ' جنيه'"></td>

                    <td class="p-4">
                        <div class="flex justify-center gap-2">
                            <button
                                @click="openEdit(item)"
                                class="px-3 py-1 rounded-md text-blue-600 hover:bg-blue-50 transition"
                            >
                                تعديل
                            </button>

                            <button
                                @click="deleteService(item.id)"
                                class="px-3 py-1 rounded-md text-red-600 hover:bg-red-50 transition"
                            >
                                حذف
                            </button>
                        </div>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>

</div>

<!-- Modal -->
<div
    x-show="showModal"
    class="fixed inset-0 bg-black/40 flex items-center justify-center p-4"
>

    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-5">

        <div class="mb-4">
            <h2 class="text-lg font-bold text-gray-800"
                x-text="editMode ? 'تعديل خدمة' : 'إضافة خدمة'"></h2>
            <p class="text-sm text-gray-500">ادخل بيانات الخدمة</p>
        </div>

        <div class="space-y-3">

            <input
                x-model="form.name"
                placeholder="اسم الخدمة"
                class="w-full bg-gray-50 border border-gray-100 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 outline-none"
            />

            <textarea
                x-model="form.description"
                placeholder="الوصف"
                class="w-full bg-gray-50 border border-gray-100 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 outline-none"
            ></textarea>

            <select
                x-model="form.doctor"
                class="w-full bg-gray-50 border border-gray-100 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 outline-none"
            >
                <option value="">اختر الدكتور</option>
                <template x-for="doc in clinicDoctors" :key="doc.id">
                    <option :value="doc.name" x-text="doc.name"></option>
                </template>
            </select>

            <input
                type="number"
                x-model="form.price"
                placeholder="السعر"
                class="w-full bg-gray-50 border border-gray-100 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 outline-none"
            />

        </div>

        <div class="flex justify-end gap-2 mt-5">
            <button
                @click="showModal = false"
                class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition"
            >
                إلغاء
            </button>

            <button
                @click="save()"
                class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition"
            >
                حفظ
            </button>
        </div>

    </div>
</div>

</div>

@endsection