<div>
    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
        <i class="fa-solid fa-phone text-teal-600"></i>
        رقم الهاتف
    </label>
    <input type="text" name="phone" value="{{ old('phone', $clinic->phone ?? '') }}"
        placeholder="01xxxxxxxxx"
        class="mt-2 w-full rounded-xl border border-gray-200 p-3
                focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none">
</div>
