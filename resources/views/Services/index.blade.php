@extends('layouts-main.dashboard')

@section('title', 'خدمات العيادة')

@section('content')

    <div x-data="{
        showModal: false,
        editMode: false,
    
        serviceDropdownOpen: false,
        doctorDropdownOpen: false,
    
        serviceCatalogs: @js($serviceCatalogs),
        doctors: @js($doctors),
        clinicServices: @js($clinicServices),
    
        filters: {
            doctorId: '',
            search: ''
        },
    
        form: {
            id: null,
            clinic_service_id: null,
            doctor_id: null,
            description: '',
            price: 0
        },
    
        get filteredServices() {
            return this.clinicServices.filter(s => {
    
                const matchDoctor = this.filters.doctorId ?
                    s.doctor_id == this.filters.doctorId :
                    true;
    
                const matchSearch = this.filters.search ?
                    (s.description ?? '')
                    .toLowerCase()
                    .includes(this.filters.search.toLowerCase()) :
                    true;
    
                return matchDoctor && matchSearch;
            });
        },
    
        openCreate() {
            this.editMode = false;
    
            this.form = {
                id: null,
                clinic_service_id: null,
                doctor_id: null,
                description: '',
                price: 0
            };
    
            this.showModal = true;
        },
    
        openEdit(item) {
            this.editMode = true;
    
            this.form = {
                id: item.id,
                clinic_service_id: item.clinic_service_id,
                doctor_id: item.doctor_id,
                description: item.description,
                price: item.price
            };
            this.showModal = true;
        },
        getToken() {
            return document.querySelector(`input[name='_token']`).value;
        },
        deleteService(id) {
            if (!confirm('متأكد؟')) return;
            fetch(`/clinic/services/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': this.getToken(),
                        'Accept': 'application/json',
                    },
                })
                .then(async (res) => {
                    const data = await res.json().catch(() => ({}));
    
                    if (!res.ok || !data.success) {
                        throw new Error(data.message || 'Delete failed');
                    }
    
                    return data;
                })
                .then(() => {
                    this.clinicServices =
                        this.clinicServices.filter(s => s.id !== id);
                })
                .catch(err => {
                    console.error(err);
                    alert('حصل خطأ أثناء الحذف');
                });
        }
    }" dir="rtl" class="p-6 bg-gray-50 min-h-screen">

        <x-clinic-services.header />

        <x-clinic-services.filters />

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="p-4 text-right">الخدمة</th>
                        <th class="p-4 text-right">الوصف</th>
                        <th class="p-4 text-right">الدكتور</th>
                        <th class="p-4 text-right">السعر</th>
                        <th class="p-4 text-center">الإجراءات</th>
                    </tr>
                </thead>

                <tbody>
                    <template x-for="item in filteredServices" :key="item.id">
                        <tr class="hover:bg-gray-50 transition">

                            <td class="p-4" x-text="item.service_name ?? ''"></td>

                            <td class="p-4 text-gray-600" x-text="item.description"></td>

                            <td class="p-4" x-text="item.doctor_name ?? ''"></td>

                            <td class="p-4 text-teal-600 font-semibold" x-text="item.price + ' جنيه'"></td>

                            <td class="p-4">
                                <div class="flex justify-center gap-2">

                                    <button @click="openEdit(item)"
                                        class="px-3 py-1 text-blue-600 hover:bg-blue-50 rounded-md">
                                        تعديل
                                    </button>

                                    <button @click="deleteService(item.id)"
                                        class="px-3 py-1 text-red-600 hover:bg-red-50 rounded-md">
                                        حذف
                                    </button>

                                </div>
                            </td>

                        </tr>
                    </template>
                </tbody>

            </table>
        </div>

        <x-clinic-services.model />

    </div>

@endsection
