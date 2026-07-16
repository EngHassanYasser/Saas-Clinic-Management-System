<div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
    <h2 class="font-semibold text-gray-800 text-sm" x-text="mode === 'add' ? 'إضافة طبيب جديد' : 'تعديل بيانات الطبيب'">
    </h2>

    <button type="button" @click="showModel = false" class="text-gray-400 hover:text-red-500 transition">
        <i class="fa fa-xmark"></i>
    </button>
</div>
