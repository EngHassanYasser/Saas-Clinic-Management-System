{{-- ===================== DELETE MODAL (clinic only) ===================== --}}
<div id="deleteModal" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center" dir="rtl">
    <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 text-center shadow-xl">
        <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
            <i class="fa fa-trash text-red-500 text-xl"></i>
        </div>
        <h3 class="text-base font-medium text-gray-800 mb-2">حذف الشكوى</h3>
        <p class="text-sm text-gray-400 mb-6">هل أنت متأكد من حذف هذه الشكوى؟</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()"
                class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">إلغاء</button>
            <button onclick="doDelete()"
                class="flex-1 bg-red-500 hover:bg-red-600 text-white text-sm py-2.5 rounded-lg transition">حذف</button>
        </div>
    </div>
</div>
