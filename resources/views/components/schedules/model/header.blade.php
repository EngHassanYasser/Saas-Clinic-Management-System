<div class="flex items-center justify-between p-5 border-b">
    <h2 class="font-semibold text-gray-800">
        <span x-text="addMode ? 'إضافة' : 'تعديل'"></span>
        موعد — <span x-text="currentDoctor?.name"></span>
    </h2>
    <button @click="showModel = false;editeMode = false;addMode = false" class="text-gray-400 hover:text-gray-600">
        <i class="fa fa-xmark"></i>
    </button>
</div>
