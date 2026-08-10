<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">القسم</label>
    <select name="department_name"
        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
        x-model="currentComplaintt.department">
        <option value>اختر القسم</option> 
        <template x-for="department in departments" :key="department.value">
        <option :value="department.value" x-text="department.label"></option>
        </template>
    </select>
</div>
