<div x-show="mode == 'update'" class="grid grid-cols-2 gap-3">
    <div>
        <label class="text-xs text-gray-500 mb-1 block">تاريخ البداية</label>
        <input x-model="form.startAt" type="date" readonly
            class="w-full border rounded-xl p-3 outline-none">
    </div>
    <div>
        <label class="text-xs text-gray-500 mb-1 block">تاريخ الانتهاء</label>
        <input x-model="form.endAt" type="date" readonly
            class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>
</div>