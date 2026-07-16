<div class="flex flex-col items-center gap-3">

    <label class="text-xs text-gray-500 self-start">
        صورة الطبيب
    </label>

    <div class="relative w-28 h-28">

        <div
            class="w-28 h-28 rounded-full border-2 border-dashed border-gray-200 overflow-hidden bg-gray-50 flex items-center justify-center hover:border-teal-400 transition">

            {{-- Preview بعد اختيار صورة --}}
            <template x-if="imagePreview">
                <img :src="imagePreview" class="w-full h-full object-cover">
            </template>

            {{-- الصورة الحالية --}}
            <template x-if="!imagePreview && currentDoctor.image">
                <img :src="currentDoctor.image" class="w-full h-full object-cover">
            </template>

            {{-- أيقونة افتراضية --}}
            <template x-if="!imagePreview && !currentDoctor.image">
                <i class="fa fa-user text-gray-300 text-3xl"></i>
            </template>

        </div>

        <label
            class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center cursor-pointer hover:bg-teal-700 transition shadow">

            <i class="fa fa-camera text-xs"></i>

            <input x-ref="imageInput" type="file" name="image" accept="image/png,image/jpeg,image/jpg,image/webp"
                class="hidden"
                @change="
        const file = $event.target.files[0];

        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('اختر صورة صحيحة');
            $event.target.value='';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('الحد الأقصى لحجم الصورة 2MB');
            $event.target.value='';
            return;
        }

        imagePreview = URL.createObjectURL(file);
    ">
        </label>

    </div>
    <div class="flex gap-2">

        <button type="button" @click="$refs.imageInput.click()"
            class="px-3 py-2 rounded-lg bg-teal-600 text-white text-xs hover:bg-teal-700">
            اختيار صورة
        </button>

        <button type="button" x-show="imagePreview" x-transition
            @click="
                imagePreview = null;
                $refs.imageInput.value='';
            "
            class="px-3 py-2 rounded-lg bg-red-500 text-white text-xs hover:bg-red-600">
            إزالة
        </button>

    </div>

    <p class="text-[11px] text-gray-400">
        JPG / PNG / WEBP — الحد الأقصى 2MB
    </p>

    @error('image')
        <p class="text-xs text-red-500">{{ $message }}</p>
    @enderror

</div>
