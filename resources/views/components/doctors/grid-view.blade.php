{{-- GRID VIEW --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

    <template x-for="doctor in filteredDoctors" :key="doctor.name">
        <div
            class="bg-white rounded-xl border border-gray-100 p-5 flex flex-col gap-3 hover:-translate-y-1 transition duration-200">

            <div class="flex items-start justify-between">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-lg font-medium flex-shrink-0"
                    :class="doctor.color">
                    <span x-text="doctor.initials"></span>
                </div>

                <span class="text-xs px-2.5 py-1 rounded-full"
                    :class="doctor.active ?
                        'bg-emerald-100 text-emerald-700' :
                        'bg-gray-100 text-gray-500'">

                    <span x-text="doctor.active ? 'متاح' : 'غير متاح'"></span>
                </span>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-800" x-text="doctor.name"></p>
                <p class="text-xs text-teal-600 mt-0.5" x-text="doctor.specialty"></p>
            </div>

            <div class="flex flex-col gap-1.5 text-xs text-gray-400">
                <div class="flex items-center gap-1.5">
                    <i class="fa fa-money-bill-wave text-gray-300 w-3"></i>
                    <span x-text="doctor.price + ' ج.م / كشف'"></span>
                </div>

                <div class="flex items-center gap-1.5">
                    <i class="fa fa-phone text-gray-300 w-3"></i>
                    <span x-text="doctor.phone"></span>
                </div>

                <div class="flex items-center gap-1.5">
                    <i class="fa fa-envelope text-gray-300 w-3"></i>
                    <span x-text="doctor.email"></span>
                </div>

                <div class="flex items-center gap-1.5">
                    <i class="fa fa-calendar-check text-gray-300 w-3"></i>
                    <span x-text="doctor.appointments + ' موعد هذا الشهر'"></span>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-auto pt-3 border-t border-gray-50">

                {{-- تعديل --}}
                <button type="button" @click="editDoctor = doctor; showEditModal = true"
                    class="flex-1 text-center text-xs text-teal-600 border border-teal-200 hover:bg-teal-50 py-1.5 rounded-lg transition">
                    <i class="fa fa-pen ml-1"></i> تعديل
                </button>

                {{-- حذف --}}
                <form action="" method="POST" x-data="{ confirmDelete: false }">

                    @csrf
                    @method('DELETE')

                    <button type="button" x-show="!confirmDelete" @click="confirmDelete = true"
                        class="w-8 h-8 flex items-center justify-center text-red-400 border border-red-200 hover:bg-red-50 rounded-lg transition">
                        <i class="fa fa-trash text-xs"></i>
                    </button>

                    <div x-show="confirmDelete" class="flex items-center gap-1">
                        <button type="submit"
                            class="text-xs text-white bg-red-500 hover:bg-red-600 px-2 py-1.5 rounded-lg transition">
                            تأكيد
                        </button>

                        <button type="button" @click="confirmDelete = false"
                            class="text-xs text-gray-500 border border-gray-200 hover:bg-gray-50 px-2 py-1.5 rounded-lg transition">
                            لأ
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </template>

    <x-doctors.edite-model />

</div>
