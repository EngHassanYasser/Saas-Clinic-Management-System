<template x-if="mode == 'update'">
    <div>
        <label class="text-xs text-gray-500">حالة الطبيب</label>

        <div class="mt-1 flex gap-3">
            <label
                class="flex-1 flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm cursor-pointer hover:border-teal-500 transition">
                <input
                    type="radio"
                    name="isActive"
                    value="1"
                    x-model="currentDoctor.isActive"
                    class="text-teal-600 focus:ring-teal-500">
                <span>متاح</span>
            </label>

            <label
                class="flex-1 flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm cursor-pointer hover:border-teal-500 transition">
                <input
                    type="radio"
                    name="isActive"
                    value="0"
                    x-model="currentDoctor.isActive"
                    class="text-teal-600 focus:ring-teal-500">
                <span>غير متاح</span>
            </label>
        </div>
    </div>
</template>