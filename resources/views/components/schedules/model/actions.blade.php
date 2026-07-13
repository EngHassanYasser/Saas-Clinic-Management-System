 <div class="flex gap-2 px-5 pb-5">
     <button type="submit"
         class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 rounded-lg transition">
                     <span x-text="addMode ? 'إضافة' : 'تعديل'"></span>
     </button>
     <button type="button" @click="showModel = false;editeMode = false;addMode = false"
         class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">
         إلغاء
     </button>
 </div>
