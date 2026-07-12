@extends('layouts-main.dashboard')

@section('title', 'خدمات العيادة')

@section('content')

    <div x-data="clinicServicesForm({
        serviceCatalogs: @js($serviceCatalogs),
        doctors: @js($doctors),
        clinicServices: @js($clinicServices)
    })" dir="rtl" class="p-6 bg-gray-50 min-h-screen">
    <button @click="console.log(clinicServices)">
        print
    </button>
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
                    <template x-for="clinicService in clinicServices" :key="clinicService.id">
                        <tr class="hover:bg-gray-50 transition">

                            <td class="p-4" x-text="clinicService.service_name ?? ''"></td>

                            <td class="p-4 text-gray-600" x-text="clinicService.description"></td>

                            <td class="p-4" x-text="clinicService.doctor_name ?? ''"></td>

                            <td class="p-4 text-teal-600 font-semibold" x-text="clinicService.price + ' جنيه'"></td>

                            <td class="p-4">
                                <div class="flex justify-center gap-2">

                                    <button @click="openEdit(clinicService)"
                                        class="px-3 py-1 text-blue-600 hover:bg-blue-50 rounded-md">
                                        تعديل
                                    </button>

                                    <button @click="deleteService(clinicService.id)"
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
