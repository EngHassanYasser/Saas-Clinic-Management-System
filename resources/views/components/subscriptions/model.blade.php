 {{-- ===== MODAL ===== --}}
 <div x-show="showModal" x-cloak x-transition.opacity
     class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50" @click.self="showModal = false">
     <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6">
         <h2 class="text-lg font-bold mb-5 flex items-center gap-2">
             <i class="fas fa-file-contract text-blue-600"></i>
             <span x-text="editId ? 'تعديل الاشتراك' : 'إضافة اشتراك'"></span>
         </h2>

         <div class="space-y-3">

             <input x-model="form.clinic"
                 class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                 placeholder="اسم العيادة">

             <select x-model="form.plan"
                 class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                 <option value="" disabled>اختر الخطة</option>
                 <option value="basic">Basic</option>
                 <option value="premium">Premium</option>
                 <option value="enterprise">Enterprise</option>
             </select>

             <input x-model="form.price" type="number"
                 class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                 placeholder="السعر (EGP)">

             <div class="grid grid-cols-2 gap-3">
                 <div>
                     <label class="text-xs text-gray-500 mb-1 block">تاريخ البداية</label>
                     <input x-model="form.start" type="date"
                         class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                 </div>
                 <div>
                     <label class="text-xs text-gray-500 mb-1 block">تاريخ الانتهاء</label>
                     <input x-model="form.end" type="date"
                         class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                 </div>
             </div>

         </div>

         <p x-show="formError" x-cloak class="text-red-500 text-xs mt-2" x-text="formError"></p>

         <div class="flex justify-end gap-2 mt-6">
             <button @click="showModal = false"
                 class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition">إلغاء</button>
             <button @click="save()"
                 class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition">حفظ</button>
         </div>

     </div>
 </div>
