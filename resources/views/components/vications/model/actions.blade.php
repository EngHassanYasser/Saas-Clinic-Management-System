 <div class="flex gap-3 mt-5">
     <button  type="button" @click="closeModal()"
         class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">
         إلغاء
     </button>
     <button type="submit"   x-text="mode == 'add' ? 'حفظ' : 'تعديل'"
         class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm py-2.5 rounded-lg transition"> 
     </button>
 </div>
