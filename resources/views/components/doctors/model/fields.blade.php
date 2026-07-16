 <div class="grid grid-cols-2 gap-4">

     <div>
         <label class="text-xs text-gray-500">اسم الطبيب</label>
         <input type="text" name="name" :value="currentDoctor.name"
             class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-100 outline-none transition">
     </div>

     <div x-data="{ open: false, selected: null }" class="relative">

         <label class="block text-xs text-gray-500 mb-1.5">
             التخصص <span class="text-red-400">*</span>
         </label>

         <!-- Button Trigger -->
         <button type="button" @click="open = !open"
             class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white text-left flex justify-between items-center">

             <span x-text="currentDoctor.speciality.id ? currentDoctor.speciality.name : 'اختار التخصص'"></span>

             <span>▼</span>
         </button>
         <!-- Hidden input -->
         <input type="hidden" name="speciality_id" :value="currentDoctor.speciality?.id">
         <!-- Dropdown -->
         <div x-show="open" @click.outside="open = false"
             class="absolute z-50 mt-2 w-full max-h-60 overflow-auto border bg-white rounded-lg shadow">

             <template x-for="speciality in specialities" :key="speciality.id">
                 <div @click="currentDoctor.speciality = speciality; open = false"
                     class="px-3 py-2 text-sm hover:bg-teal-50 cursor-pointer" x-text="speciality.name">
                 </div>
             </template>

         </div>
         @error('speciality_id')
             <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
         @enderror
     </div>

     <div>
         <label class="text-xs text-gray-500">الموبايل</label>
         <input type="text" name="phone" :value="currentDoctor.phone"
             class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-100 outline-none transition">
     </div>

     <div>
         <label class="text-xs text-gray-500">الإيميل</label>
         <input type="email" name="email" :value="currentDoctor.email"
             class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-100 outline-none transition">
     </div>

 </div>
