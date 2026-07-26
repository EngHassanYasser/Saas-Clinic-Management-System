<div x-show="currencSection === clinicSection" x-transition class="fade-in">
    <label class="block text-sm font-semibold text-gray-800 mb-2">
        العيادة
        <button type="button" class="text-xs text-teal-600 font-normal" @click="goToStep(specialitySection)">
            (تغيير التخصص)
        </button>
    </label>
    <select x-model="clinicId" @change="onClinicChange()"
        class="w-full p-3 rounded-xl border-2 border-gray-100 bg-gray-50 text-sm font-medium text-gray-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 focus:outline-none">
        <option value="" disabled>اختر العيادة</option>
        <template x-for="cl in filteredClinics" :key="cl.id">
            <option :value="cl.id" x-text="cl.name + ' — ' + cl.area"></option>
        </template>
    </select>
</div>
