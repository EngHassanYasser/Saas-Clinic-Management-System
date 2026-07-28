<div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-8 py-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold" x-text="mode == 'update' ? 'تعديل الباقة' : 'إضافة باقة'">
            </h2>
            <p class="mt-1 text-indigo-100"> قم بإدخال بيانات الباقة ثم اضغط حفظ.</p>
        </div>
        <button @click="closeModal()"
            class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition hover:bg-white/20">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
</div>
