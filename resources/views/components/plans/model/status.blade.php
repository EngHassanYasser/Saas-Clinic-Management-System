<div>
    <label class="mb-2 block text-sm font-semibold text-gray-700"> الحالة</label>
    <select x-model="form.status" name="status"
        class="w-full rounded-xl border border-gray-300 px-4 py-3 transition focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
        <option :value='statuses.ACTIVE'>
            نشطة
        </option>
        <option :value='statuses.INACTIVE'>
            موقوفة
        </option>
        <option :value='statuses.ARCHIVED'>مؤرشفه</option>
    </select>
</div>