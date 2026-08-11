<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">نوع المشكلة</label>
    <select name="issueType"
    x-model="currentComplaint.issueType"
        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
        <option value="">اختر النوع</option>
        <template x-for="issueType in issueTypes" :key="issueType.value">
            <option :value="issueType.value" x-text="issueType.label"
            :selected="issueType.value === '{{ old('issueType') }}'"></option>
        </template>
    </select>
</div>
