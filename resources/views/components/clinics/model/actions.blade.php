<div class="flex gap-3 px-6 pb-6">
    <button @click="save()"
        class="flex-1 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-sm font-medium py-2.5 rounded-xl transition-all">
        <i class="fas fa-floppy-disk ml-1.5"></i> حفظ
    </button>
    <button @click="showModal = false"
        class="flex-1 border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium py-2.5 rounded-xl transition">
        إلغاء
    </button>
</div>
