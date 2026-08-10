<div>
    <label class="block text-xs text-gray-500 mb-1.5">
        الطبيب
        <span class="text-red-400">*</span>
    </label>
    <select x-model="selectedVacation.doctor_id" name="doctor_id"
        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">

        <option value="">
            اختر الطبيب
        </option>

        <template x-for="doctor in doctors" :key="doctor.id">
            <option
                :class="{ 'selected': doctor.id === selectedVacation.doctor.id }" :value="doctor.id"
                x-text="doctor.name">
            </option>
        </template>
    </select>
</div>
