<div class="flex gap-3 pt-2">

    <button @click="updateDoctor(doctor.id)"
        class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 rounded-lg transition shadow-sm">
        حفظ التعديلات
    </button>

    <button type="button" @click="showEditModal = false;imagePreview=null"
        class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">
        إلغاء
    </button>

</div>
