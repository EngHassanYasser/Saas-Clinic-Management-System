<p x-show="formError" x-cloak class="text-red-500 text-xs mt-2" x-text="formError"></p>
<div class="flex justify-end gap-2 mt-6">
    <button @click="showModal = false"
        class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition">إلغاء</button>
        <button type="submit"
            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition" x-text="mode =='update' ? 'تعديل':'حفظ'"></button>
</div>
