<div x-show="currencSection === doctorSection" x-transition class="fade-in">
    <label class="block text-sm font-semibold text-gray-800 mb-2">
        الدكتور
        <button type="button" class="text-xs text-teal-600 font-normal" @click="goToStep(clinicSection)">
            (تغيير العيادة)
        </button>
    </label>
    <select x-model="doctorId" @change="onDoctorChange()"
        class="w-full p-3 rounded-xl border-2 border-gray-100 bg-gray-50 text-sm font-medium text-gray-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 focus:outline-none">
        <option value="" disabled>اختر الدكتور</option>
        <template x-for="doc in doctors" :key="doc.id">
            <option :value="doc.id" x-text="doc.name">
            </option>
        </template>
    </select>
</div>
