<div class="flex items-center justify-between mb-5">

    <h3 class="text-base font-medium text-gray-800"
        x-text="selectedVacation ? 'تعديل الإجازة' : 'إضافة إجازة'">
    </h3>

    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition">

        <i class="fa fa-xmark"></i>

    </button>

</div>
