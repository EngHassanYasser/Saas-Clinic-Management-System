 <div class="grid grid-cols-2 gap-3">
     <div>
         <label class="block text-xs text-gray-500 mb-1.5">
             من
             <span class="text-red-400">*</span>
         </label>
         <input type="date" x-model="selectedVacation.start_date" name="start_date"
             class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
     </div>
     <div>
         <label class="block text-xs text-gray-500 mb-1.5">
             إلى
             <span class="text-red-400">*</span>
         </label>
         <input type="date" x-model="selectedVacation.end_date" name="end_date"
             class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
     </div>
 </div>
