<div x-show="showDelete" x-cloak @click.self="showDelete = false" @keydown.escape.window="showDelete = false"
    x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <div x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 overflow-hidden">
        <div class="p-6 text-center">
            <div
                class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-400 text-2xl mx-auto mb-4">
                <i class="fas fa-trash-can"></i>
            </div>
            <h3 class="text-base font-bold text-gray-800 mb-1">تأكيد الحذف</h3>
            <p class="text-sm text-gray-500 mt-1">
                هل أنت متأكد من حذف
                <span class="font-semibold text-gray-700" x-text="deleteTarget?.name"></span>؟
                <br><span class="text-xs text-red-400">لا يمكن التراجع عن هذا الإجراء.</span>
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-6">
            <button @click="confirmDelete()"
                class="flex-1 bg-red-500 hover:bg-red-600 active:scale-95 text-white text-sm font-medium py-2.5 rounded-xl transition-all">
                <i class="fas fa-trash ml-1.5"></i> حذف
            </button>
            <button @click="showDelete = false"
                class="flex-1 border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium py-2.5 rounded-xl transition">
                إلغاء
            </button>
        </div>
    </div>
</div>
