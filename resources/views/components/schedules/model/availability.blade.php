<div>
    <label class="block text-xs text-gray-500 mb-1.5">
        هل الموعد متاح
    </label>

    <select name="is_available"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">

        <option value="1" :selected="editSchedule.is_available == 1">
            نعم
        </option>

        <option value="0" :selected="editSchedule.is_available == 0">
            لا
        </option>

    </select>
</div>
