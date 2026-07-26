<div x-show="showModal" x-cloak @click.self="showModal = false" @keydown.escape.window="showModal = false"
    x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <div x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-90 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
        class="bg-white w-full max-w-lg mx-4 rounded-2xl shadow-xl overflow-hidden">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-800" x-text="editId ? 'تعديل الإعلان' : 'إضافة إعلان جديد'">
            </h2>
            <button @click="showModal = false"
                class="w-8 h-8 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 flex items-center justify-center transition">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <div class="p-6 space-y-4">

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">عنوان الإعلان <span
                        class="text-red-400">*</span></label>
                <input x-model="form.title" type="text" placeholder="مثال: خصم 30% على الكشف"
                    class="w-full border border-gray-200 bg-gray-50 text-sm px-3 py-2.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">وصف الإعلان <span
                        class="text-red-400">*</span></label>
                <textarea x-model="form.desc" rows="3" placeholder="اكتب تفاصيل الإعلان هنا..."
                    class="w-full border border-gray-200 bg-gray-50 text-sm px-3 py-2.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 transition resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">الحالة</label>
                <select x-model="form.status"
                    class="w-full border border-gray-200 bg-gray-50 text-sm px-3 py-2.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>

        </div>

        <div x-show="formError" x-cloak
            class="mx-6 mb-4 text-xs text-red-500 bg-red-50 border border-red-100 px-3 py-2 rounded-lg">
            <i class="fas fa-circle-exclamation ml-1"></i> يرجى ملء جميع الحقول المطلوبة.
        </div>

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

    </div>
</div>
