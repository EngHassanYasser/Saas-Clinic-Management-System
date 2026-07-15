
  <div>
     <label class="block text-sm font-medium text-gray-700 mb-2">الدكتور</label>
     <select name="doctor_id"
         class="w-full rounded-lg border border-red-100 bg-red-50 px-4 py-2.5 focus:ring-2 focus:ring-red-400 outline-none transition">
         <option value="">اختر  الدكتور</option>
         <template x-for="doctor in doctors" :key="doctor.value">
             <option :value="doctor.id" x-text="doctor.name"
               :selected="doctor.id === '{{ old('doctor_id') }}'"></option>
         </template>
     </select>
 </div>
