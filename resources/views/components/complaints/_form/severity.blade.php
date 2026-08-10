<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">درجة الخطورة</label>
    <select name="severity"
        class="w-full rounded-lg border border-red-100 bg-red-50 px-4 py-2.5 focus:ring-2 focus:ring-red-400 outline-none transition">
        <option value="">اختر درجة الخطوره</option>
        <template x-for="severity in severities" :key="severity.value">
            <option :value="severity.value" x-text="severity.label"
            :selected="severity.value === '{{ old('severity','low') }}'"></option>
        </template>
    </select>
</div>
