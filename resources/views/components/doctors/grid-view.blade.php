<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    <template x-for="doctor in doctors" :key="doctor.name">
        <div
            class="bg-white rounded-xl border border-gray-100 p-5 flex flex-col gap-3 hover:-translate-y-1 transition duration-200">
            <x-doctors.grid_view.doctor_image />
            <div class="flex items-start justify-between">
                <x-doctors.grid_view.doctor_initials />
                <x-doctors.grid_view.doctor_availability />
            </div>
            <x-doctors.grid_view.doctor_info />
            <div class="flex flex-col gap-1.5 text-xs text-gray-400">
                <x-doctors.grid_view.consultation_fee />
                <x-doctors.grid_view.doctor_phone />
                <x-doctors.grid_view.doctor_email />
                <x-doctors.grid_view.month_appointment_count />
            </div>
            <x-doctors.grid_view.actions />
    </template>
</div>
