<div class="col-span-2">
    <input type="hidden" x-model="form.owner.gendor" name="gendor">

    <label class="block text-sm font-medium text-gray-700 mb-2">
        النوع
    </label>

    <div class="grid grid-cols-2 gap-2">

        <!-- Male -->
        <button type="button" @click="form.owner.gendor = 'male'"
            :class="form.owner.gendor === 'male'
                ?
                'border-blue-600 bg-blue-50 text-blue-700' :
                'border-gray-300 bg-white text-gray-600 hover:bg-gray-50'"
            class="flex items-center justify-center gap-2 rounded-md border px-3 py-2 text-sm font-medium transition-all duration-200">
            <i class="fa-solid fa-mars text-xs"></i>
            <span>ذكر</span>
        </button>

        <!-- Female -->
        <button type="button" @click="form.owner.gendor = 'female'"
            :class="form.owner.gendor === 'female'
                ?
                'border-pink-600 bg-pink-50 text-pink-700' :
                'border-gray-300 bg-white text-gray-600 hover:bg-gray-50'"
            class="flex items-center justify-center gap-2 rounded-md border px-3 py-2 text-sm font-medium transition-all duration-200">
            <i class="fa-solid fa-venus text-xs"></i>
            <span>أنثى</span>
        </button>

    </div>

    @error('gendor')
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
