 <p class="text-center text-sm font-semibold text-gray-700 mb-3">
     أيام العمل
 </p>

 <div class="flex flex-wrap justify-center gap-4 mb-8">
     <template x-for="day in days" :key="day.id">
         <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
             <input type="checkbox" name="workDays[]" :value="day.id"
                 :checked="currentClinic.days.some(d => d.id === day.id)" class="accent-teal-600">

             <span x-text="day.name"></span>
         </label>
     </template>
 </div>
