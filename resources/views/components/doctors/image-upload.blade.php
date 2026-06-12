  {{-- رفع الصورة --}}
  <div class="bg-white rounded-xl border border-gray-100 p-5">
      <h2 class="text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
          <i class="fa fa-image text-teal-500"></i> صورة الطبيب
      </h2>
      <div class="flex flex-col items-center">
          <div class="w-28 h-28 rounded-full border-2 border-dashed border-gray-200
                                    flex flex-col items-center justify-center text-gray-300 mb-4
                                    overflow-hidden cursor-pointer hover:border-teal-300 transition"
              @click="$refs.imageInput.click()">
              <template x-if="!imagePreview">
                  <div class="flex flex-col items-center">
                      <i class="fa fa-camera text-2xl mb-1"></i>
                      <span class="text-xs">رفع صورة</span>
                  </div>
              </template>
              <template x-if="imagePreview">
                  <img :src="imagePreview" class="w-full h-full object-cover">
              </template>
          </div>
          <input type="file" name="image" x-ref="imageInput" accept="image/*" class="hidden"
              @change="
                                const file = $event.target.files[0];
                                if (!file) return;
                                const reader = new FileReader();
                                reader.onload = e => imagePreview = e.target.result;
                                reader.readAsDataURL(file);
                            ">
          <p class="text-xs text-gray-400 text-center">JPG, PNG — حجم أقصى 2MB</p>
          @error('image')
              <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
          @enderror
      </div>
  </div>
