    {{-- المواعيد --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <h2 class="text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
            <i class="fa fa-clock text-teal-500"></i> أوقات العمل والبريك
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1.5">بداية الدوام <span class="text-red-400">*</span></label>
                <input type="time" name="work_start" value="{{ old('work_start') }}" @change="generateSlots()"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                @error('work_start')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1.5">نهاية الدوام <span
                        class="text-red-400">*</span></label>
                <input type="time" name="work_end" value="{{ old('work_end') }}" @change="generateSlots()"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                @error('work_end')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1.5">بداية البريك</label>
                <input type="time" name="break_start" value="{{ old('break_start') }}" @change="generateSlots()"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1.5">نهاية البريك</label>
                <input type="time" name="break_end" value="{{ old('break_end') }}" @change="generateSlots()"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
            </div>
        </div>

        {{-- Preview --}}
        <div class="mt-4 p-4 bg-teal-50 rounded-lg" x-show="slots.length > 0" x-transition>
            <p class="text-xs font-medium text-teal-700 mb-2">
                <i class="fa fa-eye ml-1"></i> معاينة المواعيد
                <span class="text-teal-500">({{ '<span x-text="slots.length"></span>' }} موعد)</span>
            </p>
            <div class="flex flex-wrap gap-2">
                <template x-for="slot in slots" :key="slot">
                    <span class="text-xs bg-white text-teal-700 border border-teal-200 px-2.5 py-1 rounded-lg"
                        x-text="slot"></span>
                </template>
            </div>
        </div>
    </div>
