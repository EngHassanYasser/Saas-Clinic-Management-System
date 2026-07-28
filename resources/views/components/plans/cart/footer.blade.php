<div class="flex items-center gap-3 border-t bg-gray-50 p-6">

    <button
        @click="openEdit(plan)"
        class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition-all duration-200 hover:bg-indigo-700 hover:shadow-lg">

        <i class="fa-solid fa-pen-to-square"></i>

        <span>تعديل</span>

    </button>

    <button
        x-show="plan.status === 'active'"
        class="flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-3 text-sm font-semibold text-red-600 transition-all duration-200 hover:bg-red-50 hover:border-red-300">

        <i class="fa-solid fa-ban"></i>

        <span>إيقاف</span>

    </button>

    <button
        x-show="plan.status === 'inactive'"
        class="flex items-center justify-center gap-2 rounded-xl border border-green-200 bg-white px-4 py-3 text-sm font-semibold text-green-600 transition-all duration-200 hover:bg-green-50 hover:border-green-300">

        <i class="fa-solid fa-circle-check"></i>

        <span>تفعيل</span>

    </button>

</div>