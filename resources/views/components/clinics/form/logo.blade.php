 <div class="flex flex-col items-center lg:items-start gap-3">
     <img id="preview" src="{{ $clinic->logo ?? 'https://via.placeholder.com/120' }}"
         class="w-28 h-28 rounded-2xl object-cover border shadow-sm">
     <label class="text-sm text-gray-600 flex items-center gap-2 cursor-pointer">
         <i class="fa-solid fa-upload text-teal-600"></i>
         تغيير الصورة
         <input type="file" name="logo" accept="image/*" onchange="previewImage(event)" class="hidden">
     </label>
 </div>
